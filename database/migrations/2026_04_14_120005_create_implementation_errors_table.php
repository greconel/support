<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('implementation_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementation_id')->constrained()->cascadeOnDelete();
            $table->string('level'); // error, critical, emergency
            $table->string('message', 1000);
            $table->string('exception_class')->nullable();
            $table->string('file')->nullable();
            $table->unsignedInteger('line')->nullable();
            $table->text('trace')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamp('created_at')->nullable();

            $table->index(['implementation_id', 'created_at']);
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('implementation_errors');
    }
};
