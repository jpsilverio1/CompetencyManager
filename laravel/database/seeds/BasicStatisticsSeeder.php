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

		$values1 = array('name' => 'users_count', 'value' => $this->getUsersCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values2 = array('name' => 'competences_count', 'value' =>  $this->getCompetencesCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values3 = array('name' => 'learningaids_count', 'value' => $this->getLearningAidsCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values4 = array('name' => 'tasks_count', 'value' => $this->getTasksCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values5 = array('name' => 'covered_competences_count', 'value' => $this->getCoveredCompetenciesCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values6 = array('name' => 'average_collaboration_level', 'value' => $this->getAverageCollaborationLevel(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values7 = array('name' => 'feasible_tasks_count', 'value' => $this->getFeasibleTasksCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		$values8 = array('name' => 'jobroles_count', 'value' => $this->getJobRolesCount(), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
		
		\DB::table('basic_statistics')->insert($values1);
		\DB::table('basic_statistics')->insert($values2);
		\DB::table('basic_statistics')->insert($values3);
		\DB::table('basic_statistics')->insert($values4);
		\DB::table('basic_statistics')->insert($values5);
		\DB::table('basic_statistics')->insert($values6);
		\DB::table('basic_statistics')->insert($values8);
		\DB::table('basic_statistics')->insert($values7);

		
    }

    public function getFeasibleTasksCount() {
        if (Schema::hasTable('tasks') && Schema::hasTable('user_competences') && Schema::hasTable('learning_aids_competencies')) {
            $tasks = \App\Task::all();
            $feasibleTasksCount = 0;
            foreach ($tasks as $task) {
                if ($task->isFeasible()) {
                    $feasibleTasksCount++;
                }
            }
            return $feasibleTasksCount;
        }
        return 0;

    }

    public function getAverageCollaborationLevel() {
        if (Schema::hasTable('answers')) {
            $a = \App\PersonalCompetenceProficiencyLevel::count() -1;
            $personal_competence_level_id_min =  \App\PersonalCompetenceProficiencyLevel::min('id');
            return ((\DB::table('answers')->avg('personal_competence_level_id')) - ($personal_competence_level_id_min)) / $a;
        }
        return 0;
    }

    public function getCoveredCompetenciesCount() {
        if (Schema::hasTable('user_competences')) {
            return  \DB::table('user_competences')->distinct("competence_id")->count("competence_id");
        }
        return 0;
    }

    public function getTableCount($tableName) {
        if (Schema::hasTable($tableName))
        {
            return \DB::table($tableName)->count();
        }
        return 0;
    }

    public function getUsersCount() {
        return $this->getTableCount('users');
    }

    public function getCompetencesCount() {
        return $this->getTableCount('competencies');
    }

    public function getLearningAidsCount() {
        return $this->getTableCount('learning_aids');
    }

    public function getTasksCount() {
        return $this->getTableCount('tasks');
    }

    public function getJobRolesCount() {
        return $this->getTableCount('jobroles');
    }

}
