<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningAid extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "learningaids";

    protected $fillable = [
        'name', 'description',
    ];


    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'learningaids_competencies')
            ->withPivot('comp_prof_level_id');
    }
    /*public function author()
    {
        return $this->belongsTo('App\User');
    }*/

    private function powerSet($in,$minLength = 1) {
        $count = count($in);
        $members = pow(2,$count);
        $return = array();
        for ($i = 0; $i < $members; $i++) {
            $b = sprintf("%0".$count."b",$i);
            $out = array();
            for ($j = 0; $j < $count; $j++) {
                if ($b{$j} == '1') $out[] = $in[$j];
            }
            if (count($out) >= $minLength) {
                $return[] = $out;
            }
        }
        return $return;
    }
    function isSubsetAcceptable($userSubset, $allLearningAidCompetencesIds) {
        foreach ($allLearningAidCompetencesIds as $competenceId => $competenceAcceptableLevels) {
            $foundUser = False;
            foreach ($userSubset as $user) {
                if ($user->hasCompetenceInAcceptableLevel($competenceId, $competenceAcceptableLevels)) {
                    $foundUser = True;
                    break;
                }
            }
            if (!$foundUser) {
                return False;
            }
        }
        return True;

    }

    function getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet, $allLearningAidCompetencesIds) {
        $suitableUserSubsets = [];
        foreach ($allSubsetsOfMyUserSet as $userSubset) {
            if ($this::isSubsetAcceptable($userSubset, $allLearningAidCompetencesIds)) {
                $suitableUserSubsets[] = $userSubset;
            }
        }
        return $suitableUserSubsets;
    }
    public function suitableAssigneesSets()
    {
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();
        $myUserSet = [];
        $allLearningAidCompetencesIdsAndLevels = [];
        $learningaidCompetences = $this->competencies;
        foreach($learningaidCompetences as $learningaidCompetence) {
            $learningaidRequiredCompetenceLevel = $learningaidCompetence->pivot->comp_prof_level_id;
            $acceptableCompetenceLevels = $allCompetenceLevels;
            if (in_array($learningaidRequiredCompetenceLevel, $allCompetenceLevels)) {
                $start = array_search($learningaidRequiredCompetenceLevel, $allCompetenceLevels);
                $acceptableCompetenceLevels = array_slice($allCompetenceLevels, $start);
            }
            $allLearningAidCompetencesIdsAndLevels[$learningaidCompetence->id] = $acceptableCompetenceLevels;
            $usersThatHaveTheCompetenceInAnAcceptableLevel = $learningaidCompetence->skilledUsers()->wherePivotIn('comp_prof_level_id', $acceptableCompetenceLevels)->get();
            if ($usersThatHaveTheCompetenceInAnAcceptableLevel->isEmpty()) {
                //there is no user that has the competency in an acceptable level
                return [];
            } else {
                foreach($usersThatHaveTheCompetenceInAnAcceptableLevel as $user) {
                    if (!array_key_exists($user->id, $myUserSet)) {
                        $myUserSet[$user->id] = $user;
                    }
                }
            }
        }

        if (count($allLearningAidCompetencesIdsAndLevels) == 0) {
            return [];
        }
        $newArray = [];
        foreach($myUserSet as $user) {
            $newArray[] = $user;
        }
        //no final myUserSet vai ter todos os usuarios que tem alguma competencia da learningaid num nivel aceitavel.
        // gerar todos os subsets desse set e ver se os usuarios tem todas as competencias

        $allSubsetsOfMyUserSet = $this::powerSet($newArray,1);
        $suitableAssigneesIdsSet = $this::getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet,$allLearningAidCompetencesIdsAndLevels);
        return $this::filterSets($suitableAssigneesIdsSet);
    }

    public function filterSets($suitableAssigneesIdsSet) {
        $result = $suitableAssigneesIdsSet;
        $flags = [];
        foreach($suitableAssigneesIdsSet as $oi) {
            $flags[] = True;
        }
        for ($i=0; $i<sizeOf($suitableAssigneesIdsSet); $i++) {
            for ($j=$i+1; $j<sizeOf($suitableAssigneesIdsSet); $j++) {
                if ($flags[$j] && sizeOf($suitableAssigneesIdsSet[$i]) < sizeOf($suitableAssigneesIdsSet[$j])) {
                    $oi = $diff = array_udiff($suitableAssigneesIdsSet[$i], $suitableAssigneesIdsSet[$j],
                        function ($obj_a, $obj_b) {
                            return $obj_a->id - $obj_b->id;
                        }
                    );
                    if (!$oi) {
                        // echo "$i is included in $j <br>";
                        $flags[$j] = False;
                    }
                }
            }
        }
        if (!in_array(False, $flags)) {
            //nada a excluir
            return $suitableAssigneesIdsSet;
        }
        $result =[];
        for ($i=0; $i<sizeOf($suitableAssigneesIdsSet); $i++) {
            if ($flags[$i]) {
                $result[] = $suitableAssigneesIdsSet[$i];
            }
        }
        return $result;

    }
}

/*
namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningAid extends Model
{
    //
    protected $table = 'learningaids';

    protected $fillable = [
        'name', 'description',
    ];

    public function unskilledUsers() {
        return $this->belongsToMany('App\User', 'learningaids_user', 'learningaids_id', 'user_id');
    }

    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'learningaids_competencies');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'learningaids_user');
    }
}
*/