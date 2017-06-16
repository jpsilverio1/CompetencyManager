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
        return $this->belongsToMany('App\User', 'user_competencies')
            ->withPivot('competency_level');
    }


    
}
