<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->text('description');

            $table->string('status')->default('new');
            $table->string('source')->default('web');
            $table->string('impact')->nullable();

            $table->boolean('ai_labelled_impact')->default(false);
            $table->boolean('ai_labelled_labels')->default(false);

            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('closed_at')->nullable();

            $table->string('motion_task_id')->nullable();
            $table->string('last_inbound_message_id')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('assigned_to');
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
