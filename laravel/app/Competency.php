<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Competency extends Model
{
    use NodeTrait;
    protected $table = 'competencies';
      /* @var array
     */
    protected $fillable = [
        'name', 'description',
    ];
    public function skilledUsersConsideringSubCategoriesAndCompetenceLevels($acceptableCompetenceLevels) {
        $multiplied = Competency::descendantsAndSelf($this->id)->map(function ($item, $key) use ($acceptableCompetenceLevels){
            return $item->skilledUsersConsideringCompetenceLevels($acceptableCompetenceLevels);
        })->filter(function ($value, $key) {
            return $value->isNotEmpty();
        });
        return $multiplied->flatten();
    }
    public function skilledUsersConsideringSubCategories() {
        $meuTeste = [];
        $competenceAndSubCompetences = Competency::descendantsAndSelf($this->id);
        foreach ($competenceAndSubCompetences as $competence) {
            foreach($competence->skilledUsers as $skilledUser) {
                if (array_key_exists($skilledUser->id, $meuTeste)) {
                    $meuTeste[$skilledUser->id]["competences"][] = $competence;
                } else {
                    $meuTeste[$skilledUser->id]["user"] = $skilledUser;
                    $meuTeste[$skilledUser->id]["competences"] = [$competence];
                }
            }
        }
        return $meuTeste;
        /*  print the array
          foreach($meuTeste as $coisa) {
            $usuario = $coisa["user"];
            $olar = "";
            foreach($coisa["competences"] as $comp) {
                $olar = $comp->name .  " || " . $olar;
            }
            echo "-*- $usuario->name | [$olar]  <br>";
        } */

        /* returning just the skilled users
        $multiplied = Competency::descendantsAndSelf($this->id)->map(function ($item, $key) {
            return $item->skilledUsers;
        })->filter(function ($value, $key) {
            return $value->isNotEmpty();
        });
        return $multiplied->flatten(); */
    }
    public function skilledUsersConsideringCompetenceLevels($acceptableCompetenceLevels) {
        return $this->belongsToMany('App\User', 'user_competences', 'competence_id', 'user_id')
            ->withPivot('competence_proficiency_level_id')
            ->wherePivotIn('competence_proficiency_level_id', $acceptableCompetenceLevels)->get();
    }

    public function skilledUsers(){
        return $this->belongsToMany('App\User','user_competences','competence_id','user_id')
            ->withPivot('competence_proficiency_level_id')
            ->join('competence_proficiency_level','competence_proficiency_level_id','=','competence_proficiency_level.id')
            ->select('users.*', 'competence_proficiency_level.name as pivot_proficiency_level_name');
    }

    public function tasksThatRequireIt(){
        return $this->belongsToMany('App\Task','task_competencies','competency_id','task_id')
            ->withPivot('competency_proficiency_level_id')
            ->join('competence_proficiency_level','competency_proficiency_level_id','=','competence_proficiency_level.id')
            ->select('tasks.*', 'competence_proficiency_level.name as pivot_proficiency_level_name');
    }

    public function learningAidsThatRequireIt() {
        return $this->belongsToMany('App\LearningAid', 'learning_aids_competencies', 'competency_id', 'learning_aid_id')
            ->withPivot('competency_proficiency_level_id');
    }

    public function teamsThatHaveIt() {
        return $this->belongsToMany('App\Team', 'team_competencies');
    }
	
	public function jobRolesThatHaveIt()
    {
        return $this->belongsToMany('App\JobRole', 'jobroles_competencies');
    }

    public function isCompetenceLevelAcceptable($userCompetence) {
        $acceptableLevels = $this->getAcceptableLevelsFromTaskCompetence();
        $oi = $this->pivot->competency_proficiency_level_id;
        $compId = $userCompetence->name;
        $tudo = $userCompetence->pivot->competence_proficiency_level_id;
        return in_array($userCompetence->pivot->competence_proficiency_level_id, $acceptableLevels);
    }

    public function getAcceptableLevelsFromTaskCompetence() {
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();
        $taskRequiredCompetenceLevel = $this->pivot->competency_proficiency_level_id;
        $acceptableCompetenceLevels = $allCompetenceLevels;
        if (in_array($taskRequiredCompetenceLevel, $allCompetenceLevels)) {
            $start = array_search($taskRequiredCompetenceLevel, $allCompetenceLevels);
            $acceptableCompetenceLevels = array_slice($allCompetenceLevels, $start);
        }
        return $acceptableCompetenceLevels;
    }
}
