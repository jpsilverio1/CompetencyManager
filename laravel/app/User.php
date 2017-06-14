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
    public function getNumberOfEndorsementsForCompetence($userEndorsements, $competence) {
        return $userEndorsements->where('competency_id',$competence->id)->count();
    }
    public function loggedUserEndorsedCompetence($shownUserEndorsements, $competence) {
        $loggedUserId = \Auth::user()->id;
        $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser = $shownUserEndorsements->where([
            ['endorser_id', '=', $loggedUserId],
            ['competency_id', '=', $competence->id],
        ])->count();
        return $numberOfTimesLoggedUserEndorsedCompetenceOfShownUser;
    }
    public function getEndorsement($competence,$profileUser) {
        $loggedUser =  \Auth::user();
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
    public function ola(){
        return $this->belongsToMany('App\Competency', 'user_endorsements','endorsed_id', 'endorser_id')
            ->withPivot('competency_id', 'endorsement_level')
            ->join('competencies', 'competency_id', 'competencies.id');
    }
    //endorsements where the current user is the endorsed entity
    public function endorsements()
    {
        return $this->belongsToMany('App\Competency', 'user_endorsements','endorsed_id', 'competency_id'/*,'endorser_id'*/)
        ->withPivot( 'competency_level');
        //->join('competencies', 'competency_id', 'competencies.id');
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
