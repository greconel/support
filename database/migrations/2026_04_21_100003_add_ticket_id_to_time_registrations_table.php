<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            $table->foreignId('ticket_id')
                ->nullable()
                ->after('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropIndex(['ticket_id']);
            $table->dropColumn('ticket_id');
        });
    }
};
