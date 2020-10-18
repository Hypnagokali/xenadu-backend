<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnMonitorRegistryIdToGoalId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goal_monitor_push_motivations', function (Blueprint $table) {
            $table->dropForeign(['monitor_registry_id']);
            $table->dropColumn('monitor_registry_id');
            $table->unsignedBigInteger('goal_id');
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
        Schema::table('goal_monitor_push_motivations', function (Blueprint $table) {
            //
        });
    }
}
