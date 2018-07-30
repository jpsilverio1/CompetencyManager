<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
      /* @var array
     */
    protected $fillable = [
        'text',
    ];

    public function personalCompetenceForThisQuestion() {
        return $this->belongsTo('App\PersonalCompetence');
    }
    
}
