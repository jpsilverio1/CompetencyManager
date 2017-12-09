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
    public function skilledUsersConsideringCompetenceLevels($acceptableCompetenceLevels) {
        return $this->belongsToMany('App\User', 'user_competences', 'competence_id', 'user_id')
            ->withPivot('competence_proficiency_level_id')
            ->wherePivotIn('competence_proficiency_level_id', $acceptableCompetenceLevels)->get();
    }
    public function skilledUsers() {
        return $this->belongsToMany('App\User', 'user_competences', 'competence_id', 'user_id')
            ->withPivot('competence_proficiency_level_id');
    }

    public function tasksThatRequireIt() {
        return $this->belongsToMany('App\Task', 'task_competencies')
            ->withPivot('competency_proficiency_level_id');
    }

    public function learningAidsThatRequireIt() {
        return $this->belongsToMany('App\LearningAid', 'learning_aids_competencies', 'competency_id', 'learning_aid_id')
            ->withPivot('competency_proficiency_level_id');
    }

    public function teamsThatHaveIt() {
        return $this->belongsToMany('App\Team', 'team_competencies');
    }
    
}
