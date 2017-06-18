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

    public function isManager() {
        return $this->level == 'manager';
        //\Auth::user()->level == 'manager'
    }

    public function getNumberOfEndorsementsForCompetence($userEndorsements, $competence)
    {
        return $userEndorsements->where('competency_id', $competence->id)->count();
    }
    public function userEndorsementsForCompetence($competenceId) {
        return $this->endorsements()->where('competency_id', $competenceId)->get();
    }
    public function computeThings($competenceId) {
        $meuMapa = [];
        $totalEndorsements = 0;
        $maximumValue = 0;
        $maximumKeys = [];
        foreach($this->userEndorsementsForCompetence($competenceId) as $competenceEndorsement) {
            $totalEndorsements++;
            $endorsementLevel = $competenceEndorsement->pivot->competency_level;
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
            ['competency_id', '=', $competenceId],
        ])->count();
        return $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser;
    }
    public function getEndorsementLevel($competenceId) {
        $loggedUserId = \Auth::user()->id;
        echo $this->endorsements()->where([
            ['endorser_id', '=', $loggedUserId],
            ['competency_id', '=', $competenceId],
        ])->first()->pivot->competency_level;
    }

    public function createdTasks()
    {
        return $this->hasMany('App\Task', 'author_id');
    }

    public function getEndorsement($competence, $profileUser)
    {
        $loggedUser = \Auth::user();
        //var_dump($profileUser->endorsements);
        echo "helo <br>";
        $endorsementsForProfileUser = $profileUser->endorsements();
        echo $this->getNumberOfEndorsementsForCompetence($this->endorsements(), $competence);
        //echo count($profileUser->endorsements()->where('competency_id',$competence->id)->get());
        echo "<br> ui";
        /*foreach ($profileUser->endorsements as $endorsement) {
            echo "* $endorsement->name<br>";
        }*/

        //echo "count($profileUser->endorsements) oola";
        // echo count($profileUser->endorsements);
        // echo count($loggedUser->endorsements);
        // echo "   olha me endorsement $competence->name $profileUser->name $loggedUser->name $profileUser->id $loggedUser->id";
    }

    //original endorsements function - delete
    public function ola()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorsed_id', 'endorser_id')
            ->withPivot('competency_id', 'endorsement_level')
            ->join('competencies', 'competency_id', 'competencies.id');
    }

    //endorsements where the current user is the endorsed entity
    public function endorsements()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorsed_id', 'competency_id'/*,'endorser_id'*/)
            ->withPivot('competency_level');
        //->join('competencies', 'competency_id', 'competencies.id');
    }

    //endorsements where the current user is the endorser entity
    public function endorsements_endorser()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements', 'endorser_id', 'competency_id'/*,'endorser_id'*/)
            ->withPivot('competency_level');
        //->join('competencies', 'competency_id', 'competencies.id');
    }

    public function addEndorsement($endorsedId, $competenceId, $competenceLevel)
    {
        $shownUser = User::find($endorsedId);
        //echo $shownUser->name;
        $numberOfEndorsementsToTheCompetence = $this->loggedUserEndorsedCompetence($shownUser->endorsements(), $competenceId);
        if ($numberOfEndorsementsToTheCompetence == 0) {
            //add
            $this->endorsements_endorser()->attach([$competenceId => ['competency_level' => $competenceLevel, 'endorsed_id' => $endorsedId]]);
        } else {
            //update endorsement
            $this->endorsements_endorser()->updateExistingPivot($competenceId, ['competency_level' => $competenceLevel]);
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
