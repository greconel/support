<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('label_ticket', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('label_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('ai_labelled')->default(false);

            $table->timestamps();

            $table->unique(['ticket_id', 'label_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('label_ticket');
        Schema::dropIfExists('labels');
    }
};
