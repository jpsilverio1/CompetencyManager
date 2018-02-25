<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class BasicStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
		$values1 = array('name' => 'users_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values2 = array('name' => 'competences_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values3 = array('name' => 'learningaids_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values4 = array('name' => 'tasks_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values5 = array('name' => 'covered_competences_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values6 = array('name' => 'average_collaboration_level', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values7 = array('name' => 'feasible_tasks_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values8 = array('name' => 'jobroles_count', 'value' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		
		\DB::table('basic_statistics')->insert($values1);
		\DB::table('basic_statistics')->insert($values2);
		\DB::table('basic_statistics')->insert($values3);
		\DB::table('basic_statistics')->insert($values4);
		\DB::table('basic_statistics')->insert($values5);
		\DB::table('basic_statistics')->insert($values6);
		\DB::table('basic_statistics')->insert($values8);
		\DB::table('basic_statistics')->insert($values7);
		
    }
}
