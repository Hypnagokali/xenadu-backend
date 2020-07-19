<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeVarcharLengthInGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goals', function (Blueprint $table) {
            // $table->dropColumn('name');
            // $table->dropColumn('description');
            // $table->string('name', 64);
            // $table->string('description', 255);
            DB::statement('ALTER TABLE goals MODIFY COLUMN name VARCHAR(64)');
            DB::statement('ALTER TABLE goals MODIFY COLUMN description VARCHAR(255)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goals', function (Blueprint $table) {
            DB::statement('ALTER TABLE goals MODIFY COLUMN name VARCHAR(191)');
            DB::statement('ALTER TABLE goals MODIFY COLUMN description VARCHAR(191)');
        });
    }
}
