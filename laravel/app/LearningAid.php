<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningAid extends Model
{
    //
    protected $table = 'learningaids';

    protected $fillable = [
        'name', 'description',
    ];

    public function unskilledUsers() {
        return $this->belongsToMany('App\User', 'learningaids_user', 'learningaids_id', 'user_id');
    }

    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'learningaids_competencies');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'learningaids_user');
    }
}
