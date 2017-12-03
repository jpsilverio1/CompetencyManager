<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'role', 'email', 'password',
    ];

    public function competences()
    {
        return $this->belongsToMany('App\Competency', 'user_competences', 'user_id', 'competence_id')
            ->withPivot('competence_proficiency_level_id');
    }


    public function hasCompetence($competenceId) {
        $userHasCompetence = $this->competences()->where("competence_id", $competenceId)->get();
        return !$userHasCompetence->isEmpty();
    }

    public function hasCompetenceInAcceptableLevel($competenceId, $acceptableCompetenceLevels) {
        $competenceAndSubcategoriesIds = Competency::descendantsAndSelf($competenceId)->pluck('id');
        $userHasCompetence = $this->competences()->whereIn("competence_id", $competenceAndSubcategoriesIds)->wherePivotIn('competence_proficiency_level_id', $acceptableCompetenceLevels)->get();
        return !$userHasCompetence->isEmpty();
    }

    public function isManager() {
        return $this->role == 'manager';
    }

    public function getNumberOfEndorsementsForCompetence($userEndorsements, $competence)
    {
        return $userEndorsements->where('competence_id', $competence->id)->count();
    }
    public function userEndorsementsForCompetence($competenceId) {
        return $this->endorsements()->where('competence_id', $competenceId)->get();
    }
    public function computeThings($competenceId) {
        $meuMapa = [];
        $totalEndorsements = 0;
        $maximumValue = 0;
        $maximumKeys = [];
        foreach($this->userEndorsementsForCompetence($competenceId) as $competenceEndorsement) {
            $totalEndorsements++;
            $endorsementLevel = $competenceEndorsement->pivot->competence_proficiency_endorsement_level_id;
            if (isset($meuMapa[$endorsementLevel])) {
                $meuMapa[$endorsementLevel]++;
            }else {
                $meuMapa[$endorsementLevel] = 1;
            }
            if ($meuMapa[$endorsementLevel] >$maximumValue) {
                $maximumValue = $meuMapa[$endorsementLevel];
                $maximumKeys = [];
                $maximumKeys[] = $endorsementLevel;
            } else {
                if ($meuMapa[$endorsementLevel] ==$maximumValue) {
                    $maximumValue = $meuMapa[$endorsementLevel];
                    $maximumKeys[] = $endorsementLevel;
                }
            }
        }
        if (isset($maximumKeys)) {
            return [($maximumValue/$totalEndorsements)*100, $this->getCompetenceProficiencyLevelNamesFromIds($maximumKeys)];
        } else {
            return [];
        }
    }
    private function getCompetenceProficiencyLevelNamesFromIds($competenceProficiencyLevelIds) {
        $names = [];
        foreach ($competenceProficiencyLevelIds as $competenceProficiencyLevelId) {
            $names[] = \App\CompetenceProficiencyLevel::findOrFail($competenceProficiencyLevelId)->name;
        }
        return $names;
    }

    public function loggedUserEndorsedCompetence($shownUserEndorsements, $competenceId)
    {
        $loggedUserId = \Auth::user()->id;
        $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser = $shownUserEndorsements->where([
            ['endorser_id', '=', $loggedUserId],
            ['competence_id', '=', $competenceId],
        ])->count();
        return $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser;
    }
    public function getEndorsementLevel($competenceId) {
        $loggedUserId = \Auth::user()->id;
        $competenceProficiencyLevelId = $this->endorsements()->where([
            ['endorser_id', '=', $loggedUserId],
            ['competence_id', '=', $competenceId],
        ])->first()->pivot->competence_proficiency_endorsement_level_id;
        return \App\CompetenceProficiencyLevel::findOrFail($competenceProficiencyLevelId)->name;
    }

    public function createdTasks()
    {
        return $this->hasMany('App\Task', 'author_id');
    }

    //endorsements where the current user is the endorsed entity
    public function endorsements()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorsed_id', 'competence_id'/*,'endorser_id'*/)
            ->withPivot('competence_proficiency_endorsement_level_id');
    }

    //endorsements where the current user is the endorser entity
    public function endorsements_endorser()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorser_id', 'competence_id'/*,'endorser_id'*/)
            ->withPivot('competence_proficiency_endorsement_level_id');
    }

    public function addEndorsement($endorsedId, $competenceId, $competenceLevel)
    {
        $shownUser = User::find($endorsedId);
        $numberOfEndorsementsToTheCompetence = $this->loggedUserEndorsedCompetence($shownUser->endorsements(), $competenceId);
        if ($numberOfEndorsementsToTheCompetence == 0) {
            //add
            $this->endorsements_endorser()->attach([$competenceId => ['competence_proficiency_endorsement_level_id' => $competenceLevel, 'endorsed_id' => $endorsedId]]);
        } else {
            //update endorsement
            $this->endorsements_endorser()->updateExistingPivot($competenceId, ['competence_proficiency_endorsement_level_id' => $competenceLevel]);
        }
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team', 'team_members');
    }
	
	/* Hector -> Métodos abaixo são temporários: não sabemos se vamos manter teams ou não.
	public function hasTeam()
	{
		return !$this->teams()->isEmpty(); // verifica se o usuário está em algum time
	}
	
	public function isOnTeam($teamId) {
		$userIsOnTeam = $this->teams()->where("team_id", $teamId)->get();
		return !$userIsOnTeam->isEmpty();
	} */
	
	// Usuário tem autorização pra inicializar ou finalizar tarefa se ele está na tarefa ou se ele é Gerente
	public function canInitializeOrFinishTask($taskId) {
		$task = Task::findOrFail($taskId);
		$thisUserIsInTask = $task->members()->where("id", $this->id)->get();
		return !$thisUserIsInTask->isEmpty() || $this->isManager(); 
	}
	
	public function answeredQuestions($taskId) {
		//$task = Task::findOrFail($taskId);
		//$answers = $task->answers()->where("judge_user_id", $this->id)->get();
		$answers = \DB::table('answers')->where([ ['judge_user_id', '=', $this->id], ['task_id', '=', $taskId] ])->get();
		return !$answers->isEmpty();
	}
	
	public function personalCompetences() {
		$evaluatedAnswers = \DB::table('answers')->where("evaluated_user_id", $this->id)->get();
		$personalCompetences = []; // array with personal Competences grades
		foreach ($evaluatedAnswers as $answer) {
			$personalCompetencesEvaluated = PersonalCompetence::findOrFail($answer["personal_competence_id"]);
			foreach($personalCompetencesEvaluated as $personalCompetenceEvaluated) {
				$personalCompetenceLevel = PersonalCompetenceLevel::findOrFail($answer["personal_competence_proficiency_level_id"]);
				$value = 0;
				// do some calculus with $value
			}			
			array_push($personalCompetences, $value);
		}
		
		return $personalCompetences;
	}

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
