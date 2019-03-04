<?php

namespace App\Http\Controllers;
use App\Competency;
use App\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competences = \Auth::user()->competencies;
        return view('home', [
             'competences' => $competences
        ]);
    }

    private function array_peek($stack) {
        if(empty($stack)) {
            return -1;
        }
        return $stack[0];
    }

    public function testa()
    {
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
    public function testat() {
        $tasks = file(base_path('resources/assets/seeds/test_task_seed.txt'));
        foreach($tasks as $taskInfo) {
            $splitTaskInfo = explode(";", $taskInfo);
            $taskTitle = $splitTaskInfo[0];
            $taskDescription =  $splitTaskInfo[1];
            $taskCreatorEmail = $splitTaskInfo[2];
            $task = new \App\Task;
            $task->title = $taskTitle;
            $task->description = $taskDescription;
           $task->author_id = \User::getByEmail($taskCreatorEmail);
            $task->save();

            \DB::table('basic_statistics')->where('name', 'tasks_count')->increment('value');
            $competenceProficiencyLevelMap = $this->getAllProficiencyLevels();
            foreach(explode("|",$splitTaskInfo[2]) as $singleCompetenceInfo) {
               $competenceInfo =  explode("\\", $singleCompetenceInfo);
               $competenceName = trim($competenceInfo[0]);
               $competenceIdAttempt = Competency::getByName($competenceName);
               if ($competenceIdAttempt) {
                   $competenceId = $competenceIdAttempt->id;
               }
               else {
                   $competenceId = -1;
               }
               $competenceProficencyLevelStr = trim($competenceInfo[1]);
               $competenceProficiencyLevelId = $competenceProficiencyLevelMap[$competenceProficencyLevelStr];
               if (array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap) && ($competenceId <> -1)) {
                   $task->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevelId]]);
               }
            }
        }
    }

    public function testau() {
        $users = file(base_path('resources/assets/seeds/teste_user_seed.txt'));
        $competenceProficiencyLevelMap = $this->getAllProficiencyLevels();
        foreach($users as $userInfo) {
            $splitUserInfo = explode(";", $userInfo);
            $userName = trim($splitUserInfo[0]);
            $userEmail = trim($splitUserInfo[1]);
            $userRole = trim($splitUserInfo[2]);
            $userPassword = trim($splitUserInfo[3]);
            echo " olar  $userName";
            echo count(explode("|",$splitUserInfo[4]));
            foreach(explode("|",$splitUserInfo[4]) as $singleCompetenceInfo) {
                $competenceInfo =  explode("\\", $singleCompetenceInfo);
                echo "c: $singleCompetenceInfo <br>";
                $competenceName = trim($competenceInfo[0]);
                $competenceIdAttempt = Competency::getByName($competenceName);
                if ($competenceIdAttempt) {
                    $competenceId = $competenceIdAttempt->id;
                }
                else {
                    $competenceId = -1;
                }
                $competenceProficencyLevelStr = trim($competenceInfo[1]);
                $competenceProficiencyLevelId = $competenceProficiencyLevelMap[$competenceProficencyLevelStr];
                echo "$competenceName - $competenceId - $competenceProficiencyLevelId <br>";
                if (array_key_exists($competenceProficencyLevelStr, $competenceProficiencyLevelMap) && ($competenceId <> -1)) {
                    //$task->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevelId]]);
                }
            }
            echo count($splitUserInfo);
            echo " - ";
            echo count(explode("|",$splitUserInfo[4]));
            echo "<br>";
        }
    }
    public function testatr() {
        echo " tudo bom";
        $learningAids = file(base_path('resources/assets/seeds/teste_learningaids_seed.txt'));
        $competenceProficiencyLevelMap = $this->getAllProficiencyLevels();
        echo count($learningAids);
        echo " <br>";
        foreach($learningAids as $learningAidInfo) {
            $splitLearningAidInfo = explode(";", $learningAidInfo);
            echo count($splitLearningAidInfo);
            echo " <br>";
            $learningAidTitle = trim($splitLearningAidInfo[0]);
            $learningAidDescription = trim($splitLearningAidInfo[1]);
            foreach(explode("|",$splitLearningAidInfo[2]) as $singleCompetenceInfo) {
                $competenceInfo =  explode("\\", $singleCompetenceInfo);
                $competenceName = trim($competenceInfo[0]);
                $competenceIdAttempt = Competency::getByName($competenceName);
                if ($competenceIdAttempt) {
                    $competenceId = $competenceIdAttempt->id;
                }
                else {
                    $competenceId = -1;
                }
                $competenceProficencyLevelStr = trim($competenceInfo[1]);
                $competenceProficiencyLevelId = $competenceProficiencyLevelMap[$competenceProficencyLevelStr];
                echo "$competenceName - $competenceId - $competenceProficencyLevelStr - $competenceProficiencyLevelId <br>";
            }
        }
    }

}
