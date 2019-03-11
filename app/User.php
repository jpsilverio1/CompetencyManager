<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'role', 'email', 'password', 'verifyToken'
    ];

    public function competences(){
        return $this->belongsToMany('App\Competency','user_competences','user_id','competence_id')
            ->withPivot('competence_proficiency_level_id')
            ->join('competence_proficiency_level','competence_proficiency_level_id','=','competence_proficiency_level.id')
            ->select('competencies.*', 'competence_proficiency_level.name as pivot_proficiency_level_name')
            ->withTimestamps()->orderBy('competencies.name');
    }

    public function averageCollaborationLevel() {
        return 	$this->collaborativeCompetencesWithAverageLevel()->pluck('avg_collab_level')->avg();

    }
	
	public function collaborativeCompetencesWithAverageLevel() {
        $a = \App\PersonalCompetenceProficiencyLevel::count() -1;
        $personal_competence_level_id_min =  \App\PersonalCompetenceProficiencyLevel::min('id');
        $collaboration_level_per_collaborative_competence = \DB::table('personal_competencies')->select('name')
            ->join('answers', 'personal_competencies.id', '=', 'answers.personal_competence_id')
            ->select(\DB::raw('((avg(personal_competence_level_id) - '.$personal_competence_level_id_min.')/'.$a.') as avg_collab_level, personal_competencies.name as name, personal_competencies.description as description'))
            ->where('evaluated_user_id','=',$this->id)
            ->groupBy('personal_competence_id', 'name', 'description')->get();
		return $collaboration_level_per_collaborative_competence;
	}
	
    public function forgettingLevel($competence) {
        $initTime = $competence->pivot->updated_at;
        $newInitTime = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $initTime);
        $newFinishTime = \Carbon\Carbon::now();
        $diff_in_weeks = $newInitTime->diffInDays($newFinishTime);

        if ($diff_in_weeks == 0) {
            return 100;
        }
        else {
            return floor((0.19 + 0.6318*pow((1+($diff_in_weeks-1)), (-0.68)))*100);
        }
    }

    public function hasCompetence($competenceId)
    {
        $userHasCompetence = $this->competences()->where("competence_id", $competenceId)->get();
        return !$userHasCompetence->isEmpty();
    }

    public function hasCompetenceInAcceptableLevel($competenceId, $acceptableCompetenceLevels)
    {
        $competenceAndSubcategoriesIds = Competency::descendantsAndSelf($competenceId)->pluck('id');
        $userHasCompetence = $this->competences()->whereIn("competence_id", $competenceAndSubcategoriesIds)->wherePivotIn('competence_proficiency_level_id', $acceptableCompetenceLevels)->get();
        return !$userHasCompetence->isEmpty();
    }

    public function isManager()
    {
        return $this->role == 'manager';
    }

    public function getNumberOfEndorsementsForCompetence($userEndorsements, $competence)
    {
        return $userEndorsements->where('competence_id', $competence->id)->count();
    }

    public function userEndorsementsForCompetence($competenceId)
    {
        return $this->endorsements()->where('competence_id', $competenceId)->get();
    }

    public function getNumberOfEndorsementsPerLevelForCompetence($competence)
    {
        $user = $this;
        $meuMapa = [];
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();
        foreach ($allCompetenceLevels as $competenceLevelId) {
            $endorsersIds = $user->endorsements()->where('competence_id', $competence->id)
                ->where('competence_proficiency_endorsement_level_id', $competenceLevelId)
                ->pluck('endorser_id')->toArray();
            $endorsers = User::whereIn('id', $endorsersIds)->get();
            $meuMapa[$competenceLevelId] = ["endorsementPerLevel" => $user->endorsements()->where('competence_id', $competence->id)->where('competence_proficiency_endorsement_level_id', $competenceLevelId)->count(),
                "proficiencyLevelName" => \App\CompetenceProficiencyLevel::findOrFail($competenceLevelId)->name,
                "endorsers" => $endorsers];
        }
        return $meuMapa;
    }


    public function getInitialsFromName(){
        $fullName = $this->name;
        $splitName = preg_split("/[\s,_-]+/", $fullName);
        $acronym = "";

        foreach ($splitName as $w) {
            $acronym .= $w[0];
        }
        return strtoupper($acronym);
    }

    public function getEndorsersPerLevel($user, $competence)
    {
        $meuMapa = [];
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();

        foreach ($allCompetenceLevels as $competenceLevelId) {
            $meuMapa[$competenceLevelId] = [$user->endorsements()->where('competence_id', $competence->id)->wherePivot('competence_proficiency_endorsement_level_id', $competenceLevelId)];
        }
        return $meuMapa;
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

    public function getEndorsementLevel($competenceId)
    {
        $loggedUserId = \Auth::user()->id;
        return $this->endorsements()->where([
            ['endorser_id', '=', $loggedUserId],
            ['competence_id', '=', $competenceId],
        ])->first()->pivot->proficiency_level_name;
    }

    public function createdTasks()
    {
        return $this->hasMany('App\Task', 'author_id')->orderBy('title');
    }
	
	public function joinedTasks()
    {
        return $this->belongsToMany('App\Task', 'task_teams', 'task_team_member_id', 'task_id');
    }

    //endorsements where the current user is the endorsed entity
    public function endorsements()
    {
        return $this->belongsToMany('App\Competency','user_endorsements','endorsed_id','competence_id')
            ->withPivot('competence_proficiency_endorsement_level_id')
            ->join('competence_proficiency_level','competence_proficiency_endorsement_level_id','=','competence_proficiency_level.id')
            ->select('competencies.*', 'competence_proficiency_level.name as pivot_proficiency_level_name', 'user_endorsements.endorser_id');
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
	
	// Usuário tem autorização pra inicializar ou finalizar tarefa se ele está na tarefa ou se ele é Gerente
	public function canInitializeOrFinishTask($taskId) {
		$task = Task::findOrFail($taskId);
		return $this->isTaskTeamMember($task) || $this->isManager();
	}
	public function isTaskTeamMember($task) {
        return $task->teamMembers->contains($this->id);
    }
	
	public function answeredQuestions($taskId) {
		$answers = \DB::table('answers')->where([ ['judge_user_id', '=', $this->id], ['task_id', '=', $taskId] ])->get();
		return !$answers->isEmpty();
	}

	public function learningAidsThisUserJoined()
    {
		return $this->belongsToMany('App\LearningAid', 'learning_aids_user', 'user_id', 'learning_aid_id');
    }

    public static function getByEmail($userEmail) {
        return User::where(['email'=>trim($userEmail)])->first();
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
