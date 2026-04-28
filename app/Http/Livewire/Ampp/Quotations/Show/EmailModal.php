<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Mail\CustomMail;
use App\Models\Email;
use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EmailModal extends Component
{
    public Quotation $quotation;
    public string $previewMail = '';
    public array $contacts = [];

    public string $subject = '';
    public string $content = <<< HTML
    Beste klant,<br /><br />In bijlage vindt u de offerte voor de geleverde diensten/producten. Alvast bedankt voor het vertrouwen in onze onderneming.<br /><br />Met vriendelijke groeten,<br />BMK Solutions
    HTML;
    public array $to = [];
    public array $cc = [];
    public array $bcc = [];
    public array $attachments = [];

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

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;

        $this->contacts = $quotation->client->contacts
            ->where('email', '!=', null)
            ->pluck('full_name_with_email', 'email')
            ->toArray();

        if ($quotation->client->email){
            $this->contacts = collect($this->contacts)->prepend($quotation->client->full_name_with_email, $quotation->client->email)->toArray();
        }
    }

    public function refresh()
    {
        $this->quotation->refresh();
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

        $this->quotation->emails()->save($email);

        foreach ($this->attachments as $a){
            if ($a == 'quotation'){
                $pdf = $this->quotation->generatePdf(base64: true);

                $email->addMediaFromBase64($pdf)
                    ->usingName($this->quotation->file_name)
                    ->usingFileName($this->quotation->file_name)
                    ->toMediaCollection('attachments', 'private');

                continue;
            }

            $media = $this->quotation->getMedia('files')->find($a);

            $media?->copy($email, 'attachments', 'private');
        }

        Mail::send(new CustomMail($email));

        $this->dispatch('close')->self();

        $this->reset(['content', 'to', 'cc', 'bcc', 'attachments', 'subject']);

        $this->dispatch('refreshMails')->to('ampp.emails.list-for-model');
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
        return view('livewire.ampp.quotations.show.email-modal');
    }
}
