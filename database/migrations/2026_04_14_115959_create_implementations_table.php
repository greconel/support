<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('implementations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('app_url')->nullable();
            $table->string('type')->default('beacon'); // beacon, manual
            $table->string('status')->default('unknown'); // online, degraded, offline, unknown
            $table->string('heartbeat_token', 128)->nullable();
            $table->string('heartbeat_path')->default('/beacon/heartbeat');
            $table->unsignedInteger('heartbeat_interval')->default(60); // seconds
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamp('last_push_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('implementations');
    }
};
