<?php

namespace App\Console\Commands;

use App\Jobs\DataImport\ImportClientsJob;
use App\Jobs\DataImport\ImportExpensesJob;
use App\Jobs\DataImport\ImportInvoicesJob;
use App\Jobs\DataImport\ImportProjectsJob;
use App\Jobs\DataImport\ImportSuppliersJob;
use App\Jobs\DataImport\ImportTimeRegistrationsJob;
use App\Jobs\DataImport\ImportUsersJob;
use App\Jobs\DataImport\ImportQuotationsJob;
use Illuminate\Console\Command;

class ImportOldAmppDataCommand extends Command
{
    protected $signature = 'migrate:importAmpp';

    protected $description = 'Import data from our old AMPP to this one. This will reset the current database in the new AMPP.';

    public function handle()
    {
        if (app()->environment('production')){
            $this->info('Import command already executed!');
            return;
        }

        $this->info('Resetting current database..');
        $this->call('migrate:fresh', ['--seed' => true]);
        $this->newLine();

        $bar = $this->output->createProgressBar(8);

        $this->info('Starting import of old data..');

        $this->info('Importing users..');
        ImportUsersJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing clients and contacts..');
        ImportClientsJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing quotations..');
        ImportQuotationsJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing invoices..');
        ImportInvoicesJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing suppliers..');
        ImportSuppliersJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing expenses..');
        ImportExpensesJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing projects..');
        ImportProjectsJob::dispatch();
        $bar->advance();
        $this->newLine();

        $this->info('Importing time registrations..');
        ImportTimeRegistrationsJob::dispatch();
        $bar->advance();
        $this->newLine();

        $bar->finish();
        $this->newLine();
        $this->info('Done importing!');
    }
}
