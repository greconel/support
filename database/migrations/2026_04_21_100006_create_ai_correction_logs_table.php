<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_correction_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('ai_impact')->nullable();
            $table->json('ai_labels')->nullable();
            $table->string('ai_skill_version')->nullable();

            $table->string('agent_impact')->nullable();
            $table->json('agent_labels')->nullable();

            $table->string('ticket_subject');
            $table->text('ticket_description_snippet');

            $table->string('correction_type');

            $table->boolean('processed')->default(false);
            $table->boolean('ignore_in_training')->default(false);
            $table->text('ignore_reason')->nullable();

            $table->timestamps();

            $table->index('ticket_id');
            $table->index('processed');
            $table->index('ignore_in_training');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_correction_logs');
    }
};
