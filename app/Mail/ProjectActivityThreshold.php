<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectActivityThreshold extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public ProjectActivity $projectActivity, public $threshold)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $project = Project::whereId($this->projectActivity->project_id)->first();
        $hours = round($this->projectActivity->actual_hours_float);
        $minutes = explode('.', $this->projectActivity->actual_hours_float);
        if(array_key_exists(1, $minutes)) {
            $minutes = round($minutes[1]*0.6);
        } else {
            $minutes = 0;
        }

        return $this->view('emails.ampp.activity_notify_threshold', [
            'activityName' => $this->projectActivity->name,
            'projectName' => $project->name,
            'hoursBooked' => $this->projectActivity->actual_hours_float,
            'hoursBudget' => $this->projectActivity->budget_in_hours,
            'threshold' => $this->threshold,
            'hours' => $hours,
            'minutes' => $minutes
        ]);
    }
}
