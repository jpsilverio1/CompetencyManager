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
		$user_count = \DB::table('users')->count();
		$competences_count = \DB::table('competencies')->count();
		$learningaids_count = \DB::table('learning_aids')->count();
		$tasks_count = \DB::table('tasks')->count();
		
		//$jobroles_count = 0;
		$jobroles_count = \DB::table('jobroles')->count();
		
		$covered_competences_count = \DB::table('user_competences')->distinct("competence_id")->count("competence_id");
		
		$personal_competence_level_id_min = \DB::table('personal_competence_proficiency_levels')->min('id');
		$personal_competence_level_id_max = \DB::table('personal_competence_proficiency_levels')->max('id');
		$average_collaboration_level = ((\DB::table('answers')->avg('personal_competence_level_id')) - ($personal_competence_level_id_min)) / ($personal_competence_level_id_max - $personal_competence_level_id_min);
		$not_feasible_tasks_count = \DB::table('task_competencies')
							->join('user_competences', function($join) {
								$join->on('task_competencies.competency_id', '=', 'user_competences.competence_id');
								$join->on('task_competencies.competency_proficiency_level_id', '>=', 'user_competences.competence_proficiency_level_id');	
							})->where('user_competences.competence_id', '=', NULL)
							->join('learning_aids_competencies', function($join) {
								$join->on('user_competences.competence_id', '=', 'learning_aids_competencies.competency_id');
								$join->on('learning_aids_competencies.competency_proficiency_level_id', '<', 'user_competences.competence_proficiency_level_id');
							})->where('learning_aids_competencies.competency_id', '=', NULL)->count("task_id");
							
		$feasible_tasks_count = $tasks_count - $not_feasible_tasks_count;

		$values1 = array('name' => 'users_count', 'value' => $user_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values2 = array('name' => 'competences_count', 'value' => $competences_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values3 = array('name' => 'learningaids_count', 'value' => $learningaids_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values4 = array('name' => 'tasks_count', 'value' => $tasks_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values5 = array('name' => 'covered_competences_count', 'value' => $covered_competences_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values6 = array('name' => 'average_collaboration_level', 'value' => $average_collaboration_level, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values7 = array('name' => 'feasible_tasks_count', 'value' => $feasible_tasks_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values8 = array('name' => 'jobroles_count', 'value' => $jobroles_count, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		/* \DB::table('basic_statistics')->insert($values1);
		\DB::table('basic_statistics')->insert($values2);
		\DB::table('basic_statistics')->insert($values3);
		\DB::table('basic_statistics')->insert($values4);
		\DB::table('basic_statistics')->insert($values5);
		\DB::table('basic_statistics')->insert($values6);
		\DB::table('basic_statistics')->insert($values8);		*/
		
		\DB::table('basic_statistics')->insert($values7);
		
    }
}
