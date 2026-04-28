<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Enums\InvoiceStatus;
use App\Enums\PeppolDocumentType;
use App\Models\Invoice;
use App\Services\InvoiceToPeppolMapper;
use App\Services\RecommandPeppolClient;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Peppol extends Component
{
    public Invoice $invoice;
    public array $peppolStatus = ['canReceive' => false, 'reason' => null];
    public array $peppolValidationErrors = [];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->checkPeppolReadiness();
    }

    public function hydrate()
    {
        $this->checkPeppolReadiness();
    }

    protected function checkPeppolReadiness(): void
    {
        if (!$this->invoice->client->validated_vat) {
            return;
        }

        $this->peppolStatus = $this->verifyPeppolRecipient();

        try {
            $peppolMapper = app(InvoiceToPeppolMapper::class);
            $peppolData = $peppolMapper->mapInvoiceToPeppolDocument($this->invoice);
            $this->peppolValidationErrors = $peppolMapper->validatePeppolData($peppolData);
        } catch (\Exception $e) {
            $this->peppolValidationErrors = ['Error: ' . $e->getMessage()];
        }
    }

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

    public function sendPeppol()
    {
        try {
            $this->sendToPeppol();

            $this->invoice->update(['status' => InvoiceStatus::Sent]);

            $this->dispatch('refreshInvoice');
        } catch (\Exception $e) {
            session()->flash('peppol_error', $e->getMessage());
        }
    }

    protected function sendToPeppol(): void
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

            Log::channel('peppol')->info('Starting Peppol send from detail page', [
                'invoice_no' => $invoice->custom_name,
                'client_vat' => $clientVat,
                'document_type' => $documentType->value,
            ]);

            $validatedAddress = null;
            $resolvedMode = null;

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
                        $validatedAddress = $peppolAddress;
                        $resolvedMode = $mode;
                        break;
                    }
                } catch (Exception $e) {
                    Log::channel('peppol')->warning("Verification failed for {$mode} mode", [
                        'invoice_no' => $invoice->custom_name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($validatedAddress === null) {
                $documentTypeLabel = $documentType === PeppolDocumentType::CreditNote ? 'credit notes' : 'invoices';
                throw new Exception("Client cannot receive {$documentTypeLabel} via Peppol. No valid Peppol address found.");
            }

            $sendResult = $this->attemptPeppolSend($invoice, $resolvedMode, $peppolClient, $peppolMapper);

            if ($sendResult['success']) {
                return;
            }

            // Fallback to alternate mode
            $alternateMode = $resolvedMode === 'vat' ? 'alternative' : 'vat';
            $alternatePeppolAddress = $peppolMapper->formatRecipientAddress($clientVat, $alternateMode);

            if ($alternatePeppolAddress !== $validatedAddress) {
                try {
                    $alternateRecipientResult = $peppolClient->verifyRecipient($alternatePeppolAddress);
                    $alternateRecipientValid = isset($alternateRecipientResult['isValid']) && $alternateRecipientResult['isValid'];

                    if ($alternateRecipientValid) {
                        $alternateDocSupportResult = $peppolClient->verifyDocumentSupport($alternatePeppolAddress, $documentType->peppolValue());
                        $alternateDocSupported = isset($alternateDocSupportResult['isValid']) && $alternateDocSupportResult['isValid'];

                        if ($alternateDocSupported) {
                            $alternateResult = $this->attemptPeppolSend($invoice, $alternateMode, $peppolClient, $peppolMapper);

                            if ($alternateResult['success']) {
                                return;
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

    protected function attemptPeppolSend(
        Invoice $invoice,
        string $identifierMode,
        RecommandPeppolClient $peppolClient,
        InvoiceToPeppolMapper $baseMapper
    ): array {
        try {
            $peppolMapper = new InvoiceToPeppolMapper($identifierMode);
            $peppolData = $peppolMapper->mapInvoiceToPeppolDocument($invoice);

            $pdf = $invoice->generatePdf(base64: true);

            if ($pdf && $pdf !== '') {
                $peppolMapper->addPdfAttachment($peppolData, $pdf, $invoice->file_name);
            }

            $validationErrors = $peppolMapper->validatePeppolData($peppolData);
            if (!empty($validationErrors)) {
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

            return [
                'success' => false,
                'error' => !empty($errorMessages) ? implode('; ', $errorMessages) : 'Unknown error from Peppol API',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    #[On('refreshInvoice')]
    public function refreshInvoice(): void
    {
        $this->invoice->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.peppol');
    }
}
