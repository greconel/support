<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdprMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('gdpr_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('when');
            $table->text('description');
            $table->string('what');
            $table->string('amount_of_details');
            $table->string('category');
            $table->string('type');
            $table->string('consequences');
            $table->string('measures');
            $table->string('notification_requirements');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_messages');
    }
}
