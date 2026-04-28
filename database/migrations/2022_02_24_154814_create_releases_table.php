<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->string('tag_name');
            $table->longText('description')->nullable();
            $table->timestamp('released_at');
            $table->boolean('is_current_release')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('releases');
    }
}
