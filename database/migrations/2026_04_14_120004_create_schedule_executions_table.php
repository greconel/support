<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementation_schedule_id')->constrained('implementation_schedules')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->integer('exit_code')->nullable();
            $table->text('output')->nullable();
            $table->string('status')->default('started'); // started, completed, failed, crashed
            $table->timestamp('created_at')->nullable();

            $table->index(['implementation_schedule_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_executions');
    }
};
