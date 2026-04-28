<?php

namespace App\Jobs;

use App\Models\Deal;
use App\Models\User;
use App\Notifications\Ampp\DealDueDateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class CheckDealsDueDateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $deals = Deal::where('due_date', '=', now()->addHour()->format('Y-m-d H:i'))->get();

        $users = User::whereRelation('roles', 'name', '=', 'super admin')->get();

        foreach ($deals as $deal) {
            Notification::send($users, new DealDueDateNotification($deal));
        }
    }
}
