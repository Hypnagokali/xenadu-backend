<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcrastinationPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procrastination_points', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->nullable(false);
            $table->string('source_name', 100);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('source_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('source_id')->references('id')->on('goals');
            $table->dateTime('assigned_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procrastination_points');
    }
}
