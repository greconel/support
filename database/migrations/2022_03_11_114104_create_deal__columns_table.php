<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealColumnsTable extends Migration
{
    public function up()
    {
        Schema::create('deal_columns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deal_columns');
    }
}
