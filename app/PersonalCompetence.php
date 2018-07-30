<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalCompetence extends Model
{
    protected $table = 'personal_competencies';
      /* @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function questionForThisCompetence() {
        return $this->hasOne('App\Question', 'question_id');
    }
    
}
