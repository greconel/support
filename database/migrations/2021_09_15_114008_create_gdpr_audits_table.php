<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdprAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('gdpr_audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('what');
            $table->text('when');
            $table->text('why');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_audits');
    }
}
