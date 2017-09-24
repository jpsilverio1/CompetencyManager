<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetenceProficiencyLevel extends Model
{
    protected $table = 'competence_proficiency_level';
    protected $fillable = [
        'name'
    ];
}
