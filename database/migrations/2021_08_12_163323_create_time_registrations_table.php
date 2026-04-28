<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('time_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->integer('total_time_in_seconds')->nullable();
            $table->json('tags')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_billable')->default(true);
            $table->boolean('is_billed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_registrations');
    }
}
