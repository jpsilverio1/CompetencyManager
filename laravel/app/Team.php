<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];


    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'team_competencies');
    }

    public function teamMembers()
    {
        return $this->belongsToMany('App\User', 'team_members');
    }
}
