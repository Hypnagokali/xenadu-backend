<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsOnGoalMonitorComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goal_monitor_comments', function (Blueprint $table) {
            $table->dropForeign(['monitor_registry_id']);
            $table->dropColumn('monitor_registry_id');

            $table->unsignedBigInteger('commenting_user_id');
            $table->unsignedBigInteger('goal_id');
            $table->dateTime('posted_at');

            $table->foreign('commenting_user_id')->references('id')->on('users');
            $table->foreign('goal_id')->references('id')->on('goals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goal_monitor_comments', function (Blueprint $table) {
            //
        });
    }
}
