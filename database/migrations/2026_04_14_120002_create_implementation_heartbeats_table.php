<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('implementation_heartbeats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementation_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->boolean('success');
            $table->timestamp('created_at')->nullable();

            $table->index(['implementation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('implementation_heartbeats');
    }
};
