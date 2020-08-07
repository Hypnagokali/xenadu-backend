<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGoalStateTransitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goal_state_transitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goal_id');
            $table->string('state');
            $table->dateTime('changed_on');
            $table->timestamps();

            $table->foreign('goal_id')->references('id')->on('goals');
            $table->foreign('state')->references('name')->on('goal_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goal_state_lookup');
    }
}
