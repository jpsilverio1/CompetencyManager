<?php

use Illuminate\Database\Seeder;
use App\Competency;
use App\User;

class WebsiteSeederForTestingPhase extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCompetencies();
        $this->seedUsers();
        $this->seedTasks();
        $this->seedLearningAids();
    }
    private function array_peek($stack) {
        if(empty($stack)) {
            return -1;
        }
        return $stack[0];
    }

    public function seedCompetencies()
    {
        echo "Seed competencies \n";
        $last_indentation = 0;
        $database_id_stack = [];
        $competenciesHierarchy = file(base_path('resources/assets/seeds/competency_hierarchy.txt'));
        $competenciesDescription = file(base_path('resources/assets/seeds/competency_descriptions.txt'));
        for ($k = 0; $k < count($competenciesHierarchy); $k++) {
            $competenceName = $competenciesHierarchy[$k];
            $i = substr_count($competenceName,'	');
            if ($i<=$last_indentation) {
                $most = ($last_indentation - $i)+1;
                for ($j = 0; $j < $most; $j++) {
                    array_shift($database_id_stack);
                }
            }
            $parent_id = $this->array_peek($database_id_stack);
            //save to database
            $competence = new \App\Competency;
            $competence->name = trim($competenceName);
            $competence->description = trim($competenciesDescription[$k]);
            if ($parent_id > 0) {
                $competence->parent_id = $parent_id;
            }
            $competence->save();
            $database_id = $competence->id;
            array_unshift($database_id_stack,$database_id);
            $last_indentation = $i;

        }
    }
    public function getAllProficiencyLevels() {
        $competenceProficiencyLevels = \App\CompetenceProficiencyLevel::all();
        $map = [];
        foreach ($competenceProficiencyLevels as $competenceProficiencyLevel) {
            $map[$competenceProficiencyLevel->name] = $competenceProficiencyLevel->id;
        }
        return $map;
    }
    public function seedTasks() {
        echo "Seed Tasks \n";
        $tasks = file(base_path('resources/assets/seeds/test_task_seed.txt'));
        foreach($tasks as $taskInfo) {
            $splitTaskInfo = explode(";", $taskInfo);
            $taskTitle = $splitTaskInfo[0];
            $taskDescription =  $splitTaskInfo[1];
            $taskCreatorEmail = $splitTaskInfo[2];
            $task = new \App\Task;
            $task->title = $taskTitle;
            $task->description = $taskDescription;
            $task->author_id = User::getByEmail($taskCreatorEmail)->id;
            $task->save();

            \DB::table('basic_statistics')->where('name', 'tasks_count')->increment('value');
            $competenceProficiencyLevelMap = $this->getAllProficiencyLevels();
            foreach(explode("|",$splitTaskInfo[3]) as $singleCompetenceInfo) {
                $competenceInfo =  explode("\\", $singleCompetenceInfo);
                $competenceName = trim($competenceInfo[0]);
                $competenceIdAttempt = Competency::getByName($competenceName);
                if ($competenceIdAttempt) {
                    $competenceId = $competenceIdAttempt->id;
                }
                else {
                    echo "competencia nao encontrada na tarefa: $competenceName \n";
                    $competenceId = -1;
                }
                $competenceProficencyLevelStr = trim($competenceInfo[1]);
                $competenceProficiencyLevelId = $competenceProficiencyLevelMap[$competenceProficencyLevelStr];
                if (array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap) && ($competenceId <> -1)) {
                    $task->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevelId]]);
                }
                if (!array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap)) {
                    echo "nivel da competencia nao encontrada na tarefa: $competenceProficencyLevelStr \n";
                }
            }
        }
    }

    public function seedUsers() {
        echo "Seed users \n";
        $users = file(base_path('resources/assets/seeds/teste_user_seed.txt'));
        $competenceProficiencyLevelMap = $this->getAllProficiencyLevels();
        foreach($users as $userInfo) {
            $splitUserInfo = explode(";", $userInfo);
            $userName = trim($splitUserInfo[0]);
            $userEmail = trim($splitUserInfo[1]);
            $userRole = trim($splitUserInfo[2]);
            $userPassword = trim($splitUserInfo[3]);
            $user = User::create([
                'name' => $userName,
                'email' => $userEmail,
                'password' => $userPassword,
                'role' => $userRole,
                'verifyToken' => NULL
            ]);
            $thisUser = User::findOrFail($user->id);
            $thisUser->status = 1;
            $thisUser->save();

            if (!empty($splitUserInfo[4])) {
                foreach (explode("|", $splitUserInfo[4]) as $singleCompetenceInfo) {
                    $competenceInfo = explode("\\", $singleCompetenceInfo);
                    $competenceName = trim($competenceInfo[0]);
                    $competenceIdAttempt = Competency::getByName($competenceName);
                    if ($competenceIdAttempt) {
                        $competenceId = $competenceIdAttempt->id;
                    } else {
                        echo "competencia nao encontrada no usuario: $competenceName \n";
                        $competenceId = -1;
                    }
                    $competenceProficencyLevelStr = trim($competenceInfo[1]);
                    if (array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap) && ($competenceId <> -1)) {
                        $thisUser->competences()->attach([$competenceId => ['competence_proficiency_level_id'=>$competenceProficiencyLevelMap[$competenceProficencyLevelStr]]]);
                    }
                    if (!array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap)) {
                        echo "nivel da competencia nao encontrada no usuario: $competenceProficencyLevelStr \n";
                    }
                }
            }
        }
    }
    public function seedLearningAids() {
        echo "Seed learning aids \n";
        $learningAids = file(base_path('resources/assets/seeds/teste_learningaids_seed.txt'));
        $competenceProficiencyLevelMap = $this->getAllProficiencyLevels();
        foreach($learningAids as $learningAidInfo) {
            $splitLearningAidInfo = explode(";", $learningAidInfo);
            $learningAidTitle = trim($splitLearningAidInfo[0]);
            $learningAidDescription = trim($splitLearningAidInfo[1]);
            $learningAid = new \App\LearningAid;
            $learningAid->name = $learningAidTitle;
            $learningAid->description = $learningAidDescription;
            $learningAid->save();
            foreach(explode("|",$splitLearningAidInfo[2]) as $singleCompetenceInfo) {
                $competenceInfo =  explode("\\", $singleCompetenceInfo);
                $competenceName = trim($competenceInfo[0]);
                $competenceIdAttempt = Competency::getByName($competenceName);
                if ($competenceIdAttempt) {
                    $competenceId = $competenceIdAttempt->id;
                }
                else {
                    echo "competencia nao encontrada no treinamento: $competenceName \n";
                    $competenceId = -1;
                }
                $competenceProficencyLevelStr = trim($competenceInfo[1]);
                if (array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap) && ($competenceId <> -1)) {
                    $learningAid->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevelMap[$competenceProficencyLevelStr]]]);
                }
                if (!array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap)) {
                    echo "nivel da competencia nao encontrada no treinamento: $competenceProficencyLevelStr \n";
                }
            }
        }
    }
}
