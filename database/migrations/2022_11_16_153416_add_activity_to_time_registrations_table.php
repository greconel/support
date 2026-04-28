<?php

use App\Models\ProjectActivity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            $table->dropColumn('tags');
            $table->foreignIdFor(ProjectActivity::class)->nullable()->after('project_id')->constrained()->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('time_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_activity_id');
            $table->json('tags')->nullable()->after('description');
        });
    }
};
