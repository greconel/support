<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('client_id')->constrained();
            $table->integer('number');
            $table->integer('status')->default(0);
            $table->decimal('amount')->nullable();
            $table->decimal('amount_with_vat')->nullable();
            $table->text('notes')->nullable();
            $table->text('pdf_comment')->nullable();
            $table->date('expiration_date')->nullable();
            $table->datetime('custom_created_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
