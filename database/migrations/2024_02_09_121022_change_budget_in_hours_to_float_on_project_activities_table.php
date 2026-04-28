<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBudgetInHoursToFloatOnProjectActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_activities', function (Blueprint $table) {
            DB::statement('ALTER TABLE project_activities MODIFY budget_in_hours FLOAT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_activities', function (Blueprint $table) {
            DB::statement('ALTER TABLE project_activities MODIFY budget_in_hours INTEGER');        });
    }
}
