<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('supplier_id')->constrained();
            $table->string('clearfacts_id')->nullable();
            $table->integer('number');
            $table->string('name');
            $table->date('invoice_date');
            $table->string('invoice_number')->nullable();
            $table->string('invoice_ogm')->nullable();
            $table->string('status')->default(0);
            $table->boolean('is_approved_for_payment')->default(false);
            $table->decimal('amount_excluding_vat')->nullable();
            $table->decimal('amount_including_vat')->nullable();
            $table->decimal('amount_vat')->nullable();
            $table->text('comment')->nullable();
            $table->text('clearfacts_comment')->nullable();
            $table->timestamp('sent_to_clearfacts_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
