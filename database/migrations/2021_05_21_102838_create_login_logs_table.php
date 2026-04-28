<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLogsTable extends Migration
{
    public function up()
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained();
            $table->string('ip_address');
            $table->text('user_agent');
            $table->boolean('via_remember_me')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_logs');
    }
}
