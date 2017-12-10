<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalCompetenceProficiencyLevel extends Model
{
    protected $table = 'personal_competence_proficiency_levels';
    protected $fillable = [
        'name'
    ];
}
