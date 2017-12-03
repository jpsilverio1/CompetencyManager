<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\AnswerFormRequest;

class AnswerController extends Controller
{
    public function addAnswer(AnswerFormRequest $request) {
		/* 
		$answer = \App\Answer;
        $answer->personal_competence_id = $request->get('personal_competence_id');
        $answer->personal_competence_proficiency_level = $request->get('personal_competence_proficiency_level');
        $answer->judge_user_id = $request->get('judge_user_id');
		$answer->evaluated_user_id = $request->get('evaluated_user_id');
		$answer->task_id = $request->get('task_id');
		$answer->save();
		*/
		
		$answersAllUsers = $request->get('personal_competence_level_id');
		$personalCompetencesId = $request->get('personal_competence_id'); // isso tbm é uma matriz da mesma forma acima
		
		print_r($request->get('personal_competence_level_id'));
		
		foreach($request->get('personal_competence_level_id') as $key => $val)
		{
			foreach ($val as $key_i => $val_i) {
				echo("personal_competence_level_id.'.$key.'.'.$key_i");
				//$rules['personal_competence_level_id.'.$key.'.'.$key_i] = 'required';
			}
		}
        
		
		
		
		$judge_user_id = $request->get('judge_user_id');
		$evaluated_users_id = $request->get('evaluated_user_id');
		$task_id = $request->get('task_id');
		
		for ($i = 0; $i < count($answersAllUsers); $i++) {
			$answersOneUser = $answersAllUsers[$i];
			
			$evaluated_user_id = $evaluated_users_id[$i];
			
			for ($j = 0; $j < count($answersOneUser); $j++) {
				$personal_competence_id = $personalCompetencesId[$j];
				$personal_competence_level_id = $answersOneUser[$j];
				
				$values = array('personal_competence_id' => $personal_competence_id, 'personal_competence_level_id' => $personal_competence_level_id, 'judge_user_id' => $judge_user_id, 'evaluated_user_id' => $evaluated_user_id, 'task_id' => $task_id);
				\DB::table('answers')->insert($values);
				return redirect("tasks/$task_id");
			}	
		}
		
    }
}
