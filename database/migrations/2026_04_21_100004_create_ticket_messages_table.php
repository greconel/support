<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('from_email');
            $table->string('from_name')->nullable();

            $table->string('direction');
            $table->string('subject')->nullable();

            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();

            $table->string('message_id')->nullable()->unique();
            $table->string('in_reply_to')->nullable();
            $table->string('internet_message_id')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->index('ticket_id');
            $table->index('direction');
            $table->index('in_reply_to');
            $table->index('internet_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
