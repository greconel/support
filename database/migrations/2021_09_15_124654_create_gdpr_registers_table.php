<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdprRegistersTable extends Migration
{
    public function up()
    {
        Schema::create('gdpr_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('processing_activity');
            $table->text('processing_activity_input');
            $table->string('processing_purpose');
            $table->text('processing_purpose_input');
            $table->string('subject_category');
            $table->text('subject_category_input');
            $table->string('data_type');
            $table->text('data_type_input');
            $table->string('receiver_type');
            $table->string('retention_period');
            $table->string('legal_basis');
            $table->text('legal_basis_input');
            $table->string('transfers_to');
            $table->string('nature_transfers');
            $table->text('nature_transfers_input');
            $table->string('technical_measures');
            $table->string('database');
            $table->string('access');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gdpr_registers');
    }
}
