<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'level', 'email', 'password',
    ];

    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'user_competencies')
        ->withPivot('competency_level');
    }

    public function endorsements()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements','endorsed_id', 'endorser_id')
        ->withPivot('competency_id', 'endorsement_level')
        ->join('competency', 'competency_id', 'competency.id');
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
