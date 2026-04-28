<?php

namespace App\Jobs;

use App\Actions\Clearfacts\UploadToClearfactsAction;
use App\Enums\ClearfactsInvoiceType;
use App\Models\Expense;
use App\Models\User;
use App\Notifications\Ampp\ClearfactsUploadedExpensesNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UploadBulkExpensesToClearfactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Collection $expenses
    ){}

    public function handle(UploadToClearfactsAction $uploadToClearfactsAction)
    {
        /** @var Expense $expense */
        foreach ($this->expenses as $expense) {
            $expense->refresh();

            $pdf = $expense->getFirstMedia();

            if (! $pdf){
                continue;
            }

            $response = $uploadToClearfactsAction->execute(
                ClearfactsInvoiceType::Purchase,
                $pdf->getPath(),
                "{$expense->name}.pdf",
                $expense->clearfacts_comment
            );

            if ($response->failed() || $response->collect()->has('errors')){
                continue;
            }

            $data = $response->collect();

            $uuid = data_get($data, 'data.uploadFile.uuid');

            if (! $uuid){
                continue;
            }

            $expense->update([
                'clearfacts_id' => $uuid,
                'sent_to_clearfacts_at' => now()
            ]);
        }

        $this->user->notify(new ClearfactsUploadedExpensesNotification($this->expenses));
    }
}
