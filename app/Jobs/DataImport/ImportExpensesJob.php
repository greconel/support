<?php

namespace App\Jobs\DataImport;

use App\Enums\ExpenseStatus;
use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportExpensesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $expenses = DB::connection('old_ampp')->table('purchase_invoices')->get();

        foreach ($expenses as $expense) {
            Expense::unguard();

            $newExpense = Expense::create([
                'id' => $expense->id,
                'supplier_id' => $expense->supplier_id,
                'clearfacts_id' => $expense->clearfacts_id,
                'number' => $expense->number,
                'name' => $expense->name,
                'invoice_date' => $expense->invoice_date,
                'invoice_number' => null,
                'invoice_ogm' => null,
                'status' => $this->getStatus($expense->status),
                'is_approved_for_payment' => $expense->approved_for_payment,
                'amount_excluding_vat' => $expense->amount_no_vat,
                'amount_including_vat' => $expense->amount,
                'amount_vat' => $expense->vat,
                'comment' => $expense->comment,
                'clearfacts_comment' => $expense->comment_clearfacts,
                'sent_to_clearfacts_at' => $expense->sent_to_clearfacts_at,
                'created_at' => $expense->created_at,
                'updated_at' => $expense->updated_at
            ]);

            Expense::reguard();

            if (app()->environment('local')){
                continue;
            }

            $newExpense
                ->addMediaFromDisk("storage/app/$expense->file_path", 'old_ampp_ftp')
                ->usingName($expense->file_name)
                ->toMediaCollection(diskName: 'private')
            ;
        }
    }

    private function getStatus(string $status): ExpenseStatus
    {
        return match ($status){
            'pending' => ExpenseStatus::Draft,
            'direct_debit' => ExpenseStatus::DirectDebit,
            'paid' => ExpenseStatus::Paid,
        };
    }
}
