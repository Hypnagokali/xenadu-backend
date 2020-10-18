<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPusherIdToGoalMonitorPushMotivations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goal_monitor_push_motivations', function (Blueprint $table) {
            $table->unsignedBigInteger('pusher_id');
            $table->foreign('pusher_id')->references('id')->on('users');

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
