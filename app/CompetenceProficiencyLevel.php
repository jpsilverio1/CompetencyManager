<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetenceProficiencyLevel extends Model
{
    protected $table = 'competence_proficiency_level';
    protected $fillable = [
        'name'
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
