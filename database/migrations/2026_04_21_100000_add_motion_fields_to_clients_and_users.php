<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('motion_project_id')->nullable()->after('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('motion_user_id')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('motion_project_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('motion_user_id');
        });
    }
};
