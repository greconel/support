<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PeppolDocumentType;
use App\Mail\CustomMail;
use App\Models\Email;
use App\Models\Invoice;
use App\Services\InvoiceToPeppolMapper;
use App\Services\RecommandPeppolClient;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmailModal extends Component
{
    public Invoice $invoice;
    public string $previewMail = '';
    public array $contacts = [];

    public string $subject = '';
    public string $content = <<< HTML
    Beste klant,<br /><br />In bijlage vindt u de factuur voor de geleverde diensten/producten. Alvast bedankt voor het vertrouwen in onze onderneming.<br /><br />Met vriendelijke groeten,<br />BMK Solutions
    HTML;
    public array $to = [];
    public array $cc = [];
    public array $bcc = [];
    public array $attachments = [];
    public array $peppolStatus = ['canReceive' => false, 'reason' => null];
    public array $peppolValidationErrors = [];

    protected $rules = [
        'content' => ['nullable', 'string'],
        'to' => ['required', 'array'],
        'to.*' => ['email'],
        'cc' => ['nullable', 'array'],
        'cc.*' => ['email'],
        'bcc' => ['nullable', 'array'],
        'bcc.*' => ['email'],
        'subject' => ['required', 'string', 'max:255'],
        'attachments' => ['nullable', 'array'],
    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;

        $this->contacts = $invoice->client->contacts
            ->where('email', '!=', null)
            ->pluck('full_name_with_email', 'email')
            ->toArray();

        if ($invoice->client->email){
            $this->contacts = collect($this->contacts)->prepend($invoice->client->full_name_with_email, $invoice->client->email)->toArray();
        }

        $this->to = [collect($this->contacts)->keys()->first()];

        // Set subject based on invoice type
        if ($invoice->type == InvoiceType::Credit) {
            $this->subject = "Credit nota {$invoice->custom_name} BMK Solutions";
        } else {
            $this->subject = "Factuur {$invoice->custom_name} BMK Solutions";
        }

        $this->attachments = ['invoice'];

        if ($this->invoice->client->validated_vat) {
            // First verify if recipient can receive via Peppol
            $this->peppolStatus = $this->verifyPeppolRecipient();

            // Validate Peppol data
            try {
                $peppolMapper = app(InvoiceToPeppolMapper::class);
                $peppolData = $peppolMapper->mapInvoiceToPeppolDocument($this->invoice);
                $this->peppolValidationErrors = $peppolMapper->validatePeppolData($peppolData);
            } catch (\Exception $e) {
                $this->peppolValidationErrors = ['Error: ' . $e->getMessage()];
            }

            // Only enable Peppol option if not already sent AND recipient can receive AND no validation errors
            if ($invoice->sent_to_recommand_at === null && $this->peppolStatus['canReceive'] && empty($this->peppolValidationErrors)) {
                $this->attachments[] = 'invoice_peppol';
            }
        }
    }

    public function refresh()
    {
        $this->invoice->refresh();
    }

    /**
     * Verify if the recipient can receive documents via Peppol
     *
     * @return array{canReceive: bool, reason?: string, address?: string, mode?: string}
     */
    protected function verifyPeppolRecipient(): array
    {
        $client = $this->invoice->client;

        if (!$client->validated_vat) {
            return ['canReceive' => false, 'reason' => __('Client has no validated VAT number')];
        }

        try {
            $peppolClient = app(RecommandPeppolClient::class);
            $peppolMapper = app(InvoiceToPeppolMapper::class);

            $clientVat = $client->validated_vat;
            $documentType = PeppolDocumentType::fromInvoice($this->invoice);
            $modesToTry = ['vat', 'alternative'];

            foreach ($modesToTry as $mode) {
                $peppolAddress = $peppolMapper->formatRecipientAddress($clientVat, $mode);

                try {
                    $recipientResult = $peppolClient->verifyRecipient($peppolAddress);
                    $recipientValid = isset($recipientResult['isValid']) && $recipientResult['isValid'];

                    if (!$recipientValid) {
                        continue;
                    }

                    $documentSupportResult = $peppolClient->verifyDocumentSupport($peppolAddress, $documentType->peppolValue());
                    $documentSupported = isset($documentSupportResult['isValid']) && $documentSupportResult['isValid'];

                    if ($documentSupported) {
                        return [
                            'canReceive' => true,
                            'address' => $peppolAddress,
                            'mode' => $mode,
                        ];
                    }
                } catch (Exception $e) {
                    // Continue to next mode
                }
            }

            $documentTypeLabel = $documentType === PeppolDocumentType::CreditNote
                ? __('credit notes')
                : __('invoices');

            return ['canReceive' => false, 'reason' => __('Recipient cannot receive :type via Peppol', ['type' => $documentTypeLabel])];

        } catch (Exception $e) {
            return ['canReceive' => false, 'reason' => __('Peppol verification failed')];
        }
    }

    public function send()
    {
        $this->validate();

        $email = new Email([
            'user_id' => auth()->id(),
            'mailable' => CustomMail::class,
            'subject' => $this->subject,
            'content' => $this->content,
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
        ]);

        $this->invoice->emails()->save($email);

        foreach ($this->attachments as $a){
            $pdf = "";

            if ($a == 'invoice'){
                $pdf = $this->invoice->generatePdf(base64: true);

                $email->addMediaFromBase64($pdf)
                    ->usingName($this->invoice->file_name)
                    ->usingFileName($this->invoice->file_name)
                    ->withCustomProperties(['permission', 'manage files for invoices'])
                    ->toMediaCollection('attachments', 'private');

                $this->invoice->update(['status' => InvoiceStatus::Sent]);

                continue;
            }

            if ($a == 'invoice_reminder'){
                $pdf = $this->invoice->generatePdf(base64: true, reminder: true);

                $email->addMediaFromBase64($pdf)
                    ->usingName($this->invoice->file_name_reminder)
                    ->usingFileName($this->invoice->file_name_reminder)
                    ->toMediaCollection('attachments', 'private');

                $this->invoice->update(['status' => InvoiceStatus::Reminder]);

                continue;
            }

            if($a == 'invoice_peppol'){
                $this->sendToPeppol($pdf);
                continue;
            }

            $media = $this->invoice->getMedia('files')->find($a);

            $media?->copy($email, 'attachments', 'private');
        }

        try {
            Mail::send(new CustomMail($email));

        } catch (\Exception $e) {
            \Log::error('Failed to send email', [
                'invoice_id' => $this->invoice->id,
                'error' => $e->getMessage(),
            ]);
        }

        $this->dispatch('close')->self();

        $this->reset(['content', 'to', 'cc', 'bcc', 'attachments', 'subject']);

        $this->dispatch('refreshMails')->to('ampp.emails.list-for-model');

        $this->dispatch('refreshInvoice');
    }

    /**
     * Send invoice to Peppol with recipient verification and fallback logic
     *
     * This method:
     * 1. Tries both identifier modes (vat, alternative) to find a valid Peppol address
     * 2. Verifies the recipient can receive the specific document type (invoice/creditnote)
     * 3. Attempts to send, with fallback to alternate mode if first attempt fails
     */
    protected function sendToPeppol(string $pdf = ''): void
    {
        $invoice = $this->invoice;
        $client = $invoice->client;

        try {
            $peppolClient = app(RecommandPeppolClient::class);
            $peppolMapper = app(InvoiceToPeppolMapper::class);

            if (!$client->validated_vat) {
                throw new Exception('Client has no validated VAT number');
            }

            $clientVat = $client->validated_vat;
            $documentType = PeppolDocumentType::fromInvoice($invoice);
            $modesToTry = ['vat', 'alternative'];

            Log::channel('peppol')->info('Starting Peppol send with verification', [
                'invoice_no' => $invoice->custom_name,
                'client_vat' => $clientVat,
                'document_type' => $documentType->value,
            ]);

            $validatedAddress = null;
            $resolvedMode = null;

            foreach ($modesToTry as $mode) {
                $peppolAddress = $peppolMapper->formatRecipientAddress($clientVat, $mode);

                Log::channel('peppol')->info("Verifying Peppol recipient with {$mode} mode", [
                    'invoice_no' => $invoice->custom_name,
                    'peppol_address' => $peppolAddress,
                ]);

                try {
                    $recipientResult = $peppolClient->verifyRecipient($peppolAddress);
                    $recipientValid = isset($recipientResult['isValid']) && $recipientResult['isValid'];

                    if (!$recipientValid) {
                        Log::channel('peppol')->info("Recipient not valid with {$mode} mode, trying next", [
                            'invoice_no' => $invoice->custom_name,
                            'peppol_address' => $peppolAddress,
                        ]);
                        continue;
                    }

                    $documentSupportResult = $peppolClient->verifyDocumentSupport($peppolAddress, $documentType->peppolValue());
                    $documentSupported = isset($documentSupportResult['isValid']) && $documentSupportResult['isValid'];

                    if ($documentSupported) {
                        $validatedAddress = $peppolAddress;
                        $resolvedMode = $mode;
                        Log::channel('peppol')->info("Recipient supports {$documentType->value} with {$mode} mode", [
                            'invoice_no' => $invoice->custom_name,
                            'peppol_address' => $peppolAddress,
                        ]);
                        break;
                    }

                    Log::channel('peppol')->info("Recipient does not support {$documentType->value} with {$mode} mode", [
                        'invoice_no' => $invoice->custom_name,
                        'peppol_address' => $peppolAddress,
                    ]);
                } catch (Exception $e) {
                    Log::channel('peppol')->warning("Verification failed for {$mode} mode, trying next", [
                        'invoice_no' => $invoice->custom_name,
                        'peppol_address' => $peppolAddress,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($validatedAddress === null) {
                $documentTypeLabel = $documentType === PeppolDocumentType::CreditNote ? 'credit notes' : 'invoices';
                throw new Exception("Client cannot receive {$documentTypeLabel} via Peppol. No valid Peppol address found.");
            }

            $sendResult = $this->attemptPeppolSend($invoice, $resolvedMode, $pdf, $peppolClient, $peppolMapper);

            if ($sendResult['success']) {
                return;
            }

            $alternateMode = $resolvedMode === 'vat' ? 'alternative' : 'vat';
            $alternatePeppolAddress = $peppolMapper->formatRecipientAddress($clientVat, $alternateMode);

            if ($alternatePeppolAddress !== $validatedAddress) {
                Log::channel('peppol')->info('Send failed, trying alternate identifier mode', [
                    'invoice_no' => $invoice->custom_name,
                    'original_mode' => $resolvedMode,
                    'alternate_mode' => $alternateMode,
                    'alternate_address' => $alternatePeppolAddress,
                    'original_error' => $sendResult['error'],
                ]);

                try {
                    $alternateRecipientResult = $peppolClient->verifyRecipient($alternatePeppolAddress);
                    $alternateRecipientValid = isset($alternateRecipientResult['isValid']) && $alternateRecipientResult['isValid'];

                    if ($alternateRecipientValid) {
                        $alternateDocSupportResult = $peppolClient->verifyDocumentSupport($alternatePeppolAddress, $documentType->peppolValue());
                        $alternateDocSupported = isset($alternateDocSupportResult['isValid']) && $alternateDocSupportResult['isValid'];

                        if ($alternateDocSupported) {
                            Log::channel('peppol')->info('Alternate address verified, retrying send', [
                                'invoice_no' => $invoice->custom_name,
                                'alternate_mode' => $alternateMode,
                            ]);

                            $alternateResult = $this->attemptPeppolSend($invoice, $alternateMode, $pdf, $peppolClient, $peppolMapper);

                            if ($alternateResult['success']) {
                                return; // Success with alternate!
                            }
                        }
                    }
                } catch (Exception $e) {
                    Log::channel('peppol')->warning('Alternate verification/send also failed', [
                        'invoice_no' => $invoice->custom_name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            throw new Exception($sendResult['error'] ?? 'Failed to send document via Peppol');

        } catch (Exception $e) {
            Log::channel('peppol')->error('Peppol invoice send failed', [
                'invoice_id' => $invoice->id,
                'invoice_no' => $invoice->custom_name,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Peppol send failed: ' . $e->getMessage());
        }
    }

    /**
     * Attempt to send the document to Peppol
     *
     * @return array{success: bool, error?: string}
     */
    protected function attemptPeppolSend(
        Invoice $invoice,
        string $identifierMode,
        string $pdf,
        RecommandPeppolClient $peppolClient,
        InvoiceToPeppolMapper $baseMapper
    ): array {
        try {
            $peppolMapper = new InvoiceToPeppolMapper($identifierMode);
            $peppolData = $peppolMapper->mapInvoiceToPeppolDocument($invoice);

            Log::channel('peppol')->info('Sending invoice to Peppol', [
                'invoice_no' => $invoice->custom_name,
                'identifier_mode' => $identifierMode,
                'document_type' => $peppolData['documentType'],
                'peppol_payload' => $peppolData,
            ]);

            if (!$pdf || $pdf === '') {
                $pdf = $invoice->generatePdf(base64: true);
            }

            if ($pdf && $pdf !== '') {
                $peppolMapper->addPdfAttachment($peppolData, $pdf, $invoice->file_name);
            }

            $validationErrors = $peppolMapper->validatePeppolData($peppolData);
            if (!empty($validationErrors)) {
                Log::channel('peppol')->error('Peppol data validation failed', [
                    'invoice_no' => $invoice->custom_name,
                    'errors' => $validationErrors,
                ]);
                return [
                    'success' => false,
                    'error' => 'Validation failed: ' . implode(', ', $validationErrors),
                ];
            }

            $companyId = config('services.recommand.company_id');
            if (!$companyId) {
                return [
                    'success' => false,
                    'error' => 'RECOMMAND_COMPANY_ID not configured',
                ];
            }

            $result = $peppolClient->sendDocument($companyId, $peppolData);

            if (isset($result['success']) && $result['success'] === true) {
                $invoice->recommand_document_id = $result['id'] ?? null;
                $invoice->sent_to_recommand_at = now();
                $invoice->save();

                Log::channel('peppol')->info('Document sent successfully to Peppol', [
                    'invoice_no' => $invoice->custom_name,
                    'document_id' => $result['id'] ?? null,
                    'identifier_mode' => $identifierMode,
                ]);

                return ['success' => true];
            }

            $errorMessages = [];
            if (isset($result['errors']) && is_array($result['errors'])) {
                foreach ($result['errors'] as $error) {
                    $errorMessages[] = is_array($error) ? implode(', ', $error) : $error;
                }
            }

            $errorMessage = !empty($errorMessages)
                ? implode('; ', $errorMessages)
                : 'Unknown error from Peppol API';

            Log::channel('peppol')->error('Peppol API returned error', [
                'invoice_no' => $invoice->custom_name,
                'errors' => $result['errors'] ?? [],
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
            ];
        } catch (Exception $e) {
            Log::channel('peppol')->error('Exception during Peppol send attempt', [
                'invoice_no' => $invoice->custom_name,
                'identifier_mode' => $identifierMode,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function preview()
    {
        $email = new Email([
            'user_id' => auth()->id(),
            'mailable' => CustomMail::class,
            'subject' => $this->subject,
            'content' => $this->content,
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
        ]);

        $mail = new CustomMail($email);

        $this->previewMail = $mail->render();

        $this->dispatch('openPreview')->self();
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.email-modal');
    }
}
