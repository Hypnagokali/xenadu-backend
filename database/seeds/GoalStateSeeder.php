<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('goal_states')->insert([
            ['name' => 'todo', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['name' => 'done', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['name' => 'postponed', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['name' => 'scheduled', 'created_at' => new DateTime(), 'updated_at' => new DateTime()]
        ]);
    }
}
