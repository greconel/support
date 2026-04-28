<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Seed the default "IT" category
        \App\Models\InvoiceCategory::create(['name' => 'IT']);
    }

    public function down()
    {
        Schema::dropIfExists('invoice_categories');
    }
};