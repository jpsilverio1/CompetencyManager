<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningAid extends Model
{
    //
    protected $fillable = [
        'name', 'description',
    ];

    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'learningaids_competencies');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'learningaids_user');
    }
}
