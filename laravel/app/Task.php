<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description',
    ];


    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'task_competencies')
        ->withPivot('competency_level');
    }
    public function author()
    {
        return $this->belongsTo('App\User');
    }
    public function suitableAssignees()
    {
        //TODO
        return $this->belongsToMany('App\Competency', 'task_competencies')
            ->withPivot('competency_level');
    }
    public function suitableTeams()
    {
        //TODO
        return $this->belongsToMany('App\Competency', 'task_competencies')
            ->withPivot('competency_level');
    }
}
