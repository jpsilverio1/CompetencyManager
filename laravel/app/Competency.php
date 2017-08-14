<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
	protected $table = 'competencies';
      /* @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function skilledUsers() {
        return $this->belongsToMany('App\User', 'user_competences', 'competence_id', 'user_id')
            ->withPivot('competence_proficiency_level_id');
    }

    public function tasksThatRequireIt() {
        return $this->belongsToMany('App\Task', 'task_competencies')
            ->withPivot('competency_proficiency_level_id');
    }

    public function teamsThatHaveIt() {
        return $this->belongsToMany('App\Team', 'team_competencies');
    }


    
}
