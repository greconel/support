<?php

namespace App\Http\Livewire\Ampp\Projects;

use App\Mail\CustomMail;
use App\Models\Email;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EmailModal extends Component
{
    public Project $project;
    public string $previewMail = '';
    public array $contacts = [];

    public string $subject = '';
    public string $content = '';
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

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->contacts = $project->client->contacts
            ->where('email', '!=', null)
            ->pluck('full_name_with_email', 'email')
            ->toArray();

        if ($project->client->email){
            $this->contacts = collect($this->contacts)->prepend($project->client->full_name_with_email, $project->client->email)->toArray();
        }
    }

    public function refresh()
    {
        $this->project->refresh();
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

        $this->project->emails()->save($email);

        foreach ($this->attachments as $a){
            $media = $this->project->getMedia('files')->find($a);

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
        return view('livewire.ampp.projects.email-modal');
    }
}
