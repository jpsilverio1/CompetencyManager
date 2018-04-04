<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\AnswerFormRequest;

use Illuminate\Support\Facades\Redirect;

use Carbon\Carbon;

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
		
		//$answersAllUsers = $request->get('personal_competence_level_id');
		$personalCompetencesId = $request->get('personal_competence_id'); 
		$judge_user_id = $request->get('judge_user_id');
		$evaluated_users_id = $request->get('evaluated_user_id');
		$task_id = $request->get('task_id');
		
		$countAnswers = count($personalCompetencesId) * count($evaluated_users_id);
		
		$evaluated_user_id = $evaluated_users_id[0];
		for ($i = 0; $i < $countAnswers; $i++) {
			if ($i == 0) {
				$j = 0;
			}
			else if (($i % count($personalCompetencesId)) == 0) {
				$j++;
				$evaluated_user_id = $evaluated_users_id[$j];
			}
			
			$personal_competence_id = $personalCompetencesId[$i % count($personalCompetencesId)];
			$personal_competence_level_id = $request->get('personal_competence_level_id'.strval($j).strval($i % count($personalCompetencesId)));
			$values = array('personal_competence_id' => $personal_competence_id, 'personal_competence_level_id' => $personal_competence_level_id, 'judge_user_id' => $judge_user_id, 'evaluated_user_id' => $evaluated_user_id, 'task_id' => $task_id);
			\DB::table('answers')->insert($values);
		}
		
		// Part 2 - add competencies to user_competencies table
		$selectedTechnicalCompetencies = $request->get('selectedCompetences');
		$selectedTechnicalCompetenciesProficiencyLevel = $request->get('selectedCompetencesProficiencyLevel');
		$user = \Auth::user();
		
		for ($k = 0; $k < count($selectedTechnicalCompetencies); $k++) {
			$techinalCompetenceId = $selectedTechnicalCompetencies[$k];
			$techinalCompetenceProficiencyLevelId = $selectedTechnicalCompetenciesProficiencyLevel[$k];
			
			if ($user->competences->contains($techinalCompetenceId)) {
				$user->competences()->updateExistingPivot($techinalCompetenceId, ['updated_at' => Carbon::now()]);
			}
			else {
				$user->competences()->attach($techinalCompetenceId, ['updated_at' => Carbon::now(), 'competence_proficiency_level_id' => $techinalCompetenceProficiencyLevelId ]);
			}
		}

		foreach($user->unreadNotifications as $notification){
		    if($notification->data['id'] == $task_id){
                $notification->markAsRead();
            }
        }

		return Redirect::route('tasks.show',$task_id)->withMessage('O formulário foi recebido com sucesso!');
		
    }
}
