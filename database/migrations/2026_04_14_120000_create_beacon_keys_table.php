<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beacon_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key', 128)->unique();
            $table->string('label')->nullable();
            $table->string('status')->default('unclaimed'); // unclaimed, claimed, disabled
            $table->foreignId('implementation_id')->nullable()->constrained('implementations')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beacon_keys');
    }
};