<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastNotifiedPercentageToProjectActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_activities', function (Blueprint $table) {
            $table->integer('last_notified_percentage')->after('budget_in_hours')->nullable();
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
            $table->dropColumn('last_notified_percentage');
        });
    }
}
