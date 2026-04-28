<?php

namespace App\Observers;

use App\Mail\CustomMail;
use App\Mail\ProjectActivityThreshold;
use App\Models\Email;
use App\Models\Project;
use App\Models\ProjectActivity;
use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TimeRegistrationObserver
{
    private array $notificationThresholdPercentages = [50, 80, 95, 100, 110, 120, 130];
    public function updated(TimeRegistration $timeRegistration) {
        $this->sendNotifications($timeRegistration);
    }

    public function created(TimeRegistration $timeRegistration) {
        $this->sendNotifications($timeRegistration);
    }

    private function sendNotifications(TimeRegistration $timeRegistration) {
        $projectActivity = ProjectActivity::whereId($timeRegistration->project_activity_id)->first();

        if($projectActivity && $projectActivity->budget_in_hours != 0) {

            $lastPercentage = $projectActivity->last_notified_percentage / 100;
            $currentPercentage = $projectActivity->actual_hours_float / $projectActivity->budget_in_hours * 100;

            $sendMailForThreshold = 0;
            foreach($this->notificationThresholdPercentages as $threshold) {
                if($lastPercentage < $threshold && $currentPercentage > $threshold) {
                    $sendMailForThreshold = $threshold;
                }
            }

            if($sendMailForThreshold > 0) {
                $recipients = User::permission('receive activity notifications')->select('email')->get()->toArray();
//                $superAdmins = User::role('Super Admin')->select('email')->get()->toArray();
                $superAdmins = [['email' => 'kevin@bmksolutions.be']];

                foreach (array_merge($recipients, $superAdmins) as $recipient) {
                    if ($recipient['email']) {
                        Mail::to($recipient['email'])
                            ->send(new ProjectActivityThreshold($projectActivity, $sendMailForThreshold));
                        $projectActivity->last_notified_percentage = $currentPercentage * 100;
                        $projectActivity->save();
                    }
                }
            }
        }
    }
}
