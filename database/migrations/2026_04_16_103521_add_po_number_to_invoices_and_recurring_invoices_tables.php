<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoNumberToInvoicesAndRecurringInvoicesTables extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('po_number')->nullable()->after('notes');
        });

        Schema::table('recurring_invoices', function (Blueprint $table) {
            $table->string('po_number')->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('po_number');
        });

        Schema::table('recurring_invoices', function (Blueprint $table) {
            $table->dropColumn('po_number');
        });
    }
}