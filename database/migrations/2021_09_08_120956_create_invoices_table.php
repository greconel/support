<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Client::class)->constrained();
            $table->foreignIdFor(Quotation::class)->nullable()->constrained();
            $table->unsignedBigInteger('parent_invoice_id')->nullable();
            $table->string('clearfacts_id')->nullable();
            $table->integer('number');
            $table->string('type')->default('debit');
            $table->string('ogm')->nullable()->unique();
            $table->integer('status')->default(0);
            $table->decimal('amount')->nullable();
            $table->decimal('amount_with_vat')->nullable();
            $table->text('notes')->nullable();
            $table->text('pdf_comment')->nullable();
            $table->date('expiration_date')->nullable();
            $table->datetime('custom_created_at');
            $table->timestamp('sent_to_clearfacts_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('parent_invoice_id')->references('id')->on('invoices')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
