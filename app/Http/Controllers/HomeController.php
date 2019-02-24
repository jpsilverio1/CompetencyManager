<?php

namespace App\Http\Controllers;

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

    public function testat() {
        $tasks = file(base_path('resources/assets/seeds/test_task_seed.txt'));
        echo count($tasks);
        foreach($tasks as $taskInfo) {
            $splitTaskInfo = explode(";", $taskInfo);
            /*echo "tamanho = ";
            echo count($splitTaskInfo);
            echo "<br>"; */
            $taskTitle = $splitTaskInfo[0];
            $taskDescription =  $splitTaskInfo[1];
            echo "nome: $taskTitle -- $taskDescription <br>";
            $task = new \App\Task;
            $task->title = $taskTitle;
            $task->description = $taskDescription;
            $task->author_id = $author_id;
            $task->save();

            \DB::table('basic_statistics')->where('name', 'tasks_count')->increment('value');

            $competenceIds = $request->get('competence_ids');
            $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
            for ($i=0; $i<sizeOf($competenceIds); $i++) {
                $competenceId = $competenceIds[$i];
                $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
                $results = $task->competencies()->where('competency_id', '=', $competenceId)->get();
                if ($results->isEmpty()) {
                    //add competency
                    $task->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevel]]);
                } else {
                    //update competency level
                    $task->competencies()->updateExistingPivot($competenceId, ['competency_proficiency_level_id'=>$competenceProficiencyLevel]);
                }
            }
            $co = count(explode("|",$splitTaskInfo[2]));
            echo " numero de competencias =  $co <br>";
            foreach(explode("|",$splitTaskInfo[2]) as $singleCompetenceInfo) {
               $competenceInfo =  explode("/", $singleCompetenceInfo);
               $competenceName = trim($competenceInfo[0]);
               $competenceProficencyLevelStr = trim($competenceInfo[1]);
               echo count(explode("/", $singleCompetenceInfo));
               echo "<br>";
                echo " * $singleCompetenceInfo <br>";
            }
        }
    }

}
