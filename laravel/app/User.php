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
        'name', 'role', 'email', 'password',
    ];

    public function competences()
    {
        return $this->belongsToMany('App\Competency', 'user_competences', 'user_id', 'competence_id')
            ->withPivot('competence_proficiency_level_id');
    }


    public function hasCompetence($competenceId) {
        $userHasCompetence = $this->competences()->where("competence_id", $competenceId)->get();
        return !$userHasCompetence->isEmpty();
    }

    public function hasCompetenceInAcceptableLevel($competenceId, $acceptableCompetenceLevels) {
        $userHasCompetence = $this->competences()->where("competence_id", $competenceId)->wherePivotIn('competence_proficiency_level_id', $acceptableCompetenceLevels)->get();
        return !$userHasCompetence->isEmpty();
    }

    public function isManager() {
        return $this->role == 'manager';
    }

    public function getNumberOfEndorsementsForCompetence($userEndorsements, $competence)
    {
        return $userEndorsements->where('competence_id', $competence->id)->count();
    }
    public function userEndorsementsForCompetence($competenceId) {
        return $this->endorsements()->where('competence_id', $competenceId)->get();
    }
    public function computeThings($competenceId) {
        $meuMapa = [];
        $totalEndorsements = 0;
        $maximumValue = 0;
        $maximumKeys = [];
        foreach($this->userEndorsementsForCompetence($competenceId) as $competenceEndorsement) {
            $totalEndorsements++;
            $endorsementLevel = $competenceEndorsement->pivot->competence_level;
            if (isset($meuMapa[$endorsementLevel])) {
                $meuMapa[$endorsementLevel]++;
            }else {
                $meuMapa[$endorsementLevel] = 1;
            }
            if ($meuMapa[$endorsementLevel] >$maximumValue) {
                $maximumValue = $meuMapa[$endorsementLevel];
                $maximumKeys = [];
                $maximumKeys[] = $endorsementLevel;
            } else {
                if ($meuMapa[$endorsementLevel] ==$maximumValue) {
                    $maximumValue = $meuMapa[$endorsementLevel];
                    $maximumKeys[] = $endorsementLevel;
                }
            }
        }
        if (isset($maximumKeys)) {
            return [($maximumValue/$totalEndorsements)*100, $maximumKeys];
        } else {
            return [];
        }

    }

    public function loggedUserEndorsedCompetence($shownUserEndorsements, $competenceId)
    {
        $loggedUserId = \Auth::user()->id;
        $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser = $shownUserEndorsements->where([
            ['endorser_id', '=', $loggedUserId],
            ['competence_id', '=', $competenceId],
        ])->count();
        return $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser;
    }
    public function getEndorsementLevel($competenceId) {
        $loggedUserId = \Auth::user()->id;
        echo $this->endorsements()->where([
            ['endorser_id', '=', $loggedUserId],
            ['competence_id', '=', $competenceId],
        ])->first()->pivot->competence_level;
    }

    public function createdTasks()
    {
        return $this->hasMany('App\Task', 'author_id');
    }

    //endorsements where the current user is the endorsed entity
    public function endorsements()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorsed_id', 'competence_id'/*,'endorser_id'*/)
            ->withPivot('competence_level');
    }

    //endorsements where the current user is the endorser entity
    public function endorsements_endorser()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorser_id', 'competence_id'/*,'endorser_id'*/)
            ->withPivot('competence_level');
    }

    public function addEndorsement($endorsedId, $competenceId, $competenceLevel)
    {
        $shownUser = User::find($endorsedId);
        $numberOfEndorsementsToTheCompetence = $this->loggedUserEndorsedCompetence($shownUser->endorsements(), $competenceId);
        if ($numberOfEndorsementsToTheCompetence == 0) {
            //add
            $this->endorsements_endorser()->attach([$competenceId => ['competence_level' => $competenceLevel, 'endorsed_id' => $endorsedId]]);
        } else {
            //update endorsement
            $this->endorsements_endorser()->updateExistingPivot($competenceId, ['competence_level' => $competenceLevel]);
        }
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team', 'team_members');
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
