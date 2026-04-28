<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdprChecklistsTable extends Migration
{
    public function up()
    {
        Schema::create('gdpr_checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('step_1')->nullable();
            $table->json('step_2')->nullable();
            $table->json('step_3')->nullable();
            $table->json('step_4')->nullable();
            $table->json('step_5')->nullable();
            $table->json('step_6')->nullable();
            $table->json('step_7')->nullable();
            $table->json('step_8')->nullable();
            $table->json('step_9')->nullable();
            $table->json('step_10')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_checklists');
    }
}
