<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('implementation_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementation_id')->constrained()->cascadeOnDelete();
            $table->string('command');
            $table->string('expression'); // cron expression
            $table->string('description')->nullable();
            $table->string('timezone')->default('UTC');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_started_at')->nullable();
            $table->timestamp('last_finished_at')->nullable();
            $table->integer('last_exit_code')->nullable();
            $table->string('ping_token', 128)->unique();
            $table->timestamps();

            $table->index(['implementation_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('implementation_schedules');
    }
};
