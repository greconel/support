<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingLinesTable extends Migration
{
    public function up()
    {
        Schema::create('billing_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_type');
            $table->string('model_id');
            $table->string('type');
            $table->integer('order')->default(1);
            $table->string('text');
            $table->decimal('price', 9, 4)->nullable();
            $table->decimal('subtotal', 9, 4)->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal('vat')->nullable();
            $table->decimal('discount')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_lines');
    }
}
