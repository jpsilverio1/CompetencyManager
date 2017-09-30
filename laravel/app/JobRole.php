<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobRole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	
	protected $table = 'jobroles';
	 
    protected $fillable = [
        'name', 'description',
    ];
	
	public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'jobroles_competencies');
    }
}
