<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description',
    ];


    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'task_competencies')
        ->withPivot('competency_level');
    }
    public function author()
    {
        return $this->belongsTo('App\User');
    }
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
    function isSubsetAcceptable($userSubset, $allTaskCompetencesIds) {
        //echo "allTaskCompetence Ids: ";
        //print_r($allTaskCompetencesIds);
        //echo "<br>";
        foreach ($allTaskCompetencesIds as $competenceId => $competenceAcceptableLevels) {
            $foundUser = False;
            foreach ($userSubset as $user) {
                //$userHasComp = $user->hasCompetenceInAcceptableLevel($competenceId, $competenceAcceptableLevels) ? "tem competencia" : "nao tem competencia";
                //echo "Competence: $competenceId User: $user->name -  $userHasComp <br> ";
                if ($user->hasCompetenceInAcceptableLevel($competenceId, $competenceAcceptableLevels)) {
                    $foundUser = True;
                    break;
                }
                if (!$foundUser) {
                    return False;
                }
            }
        }
        return True;

    }
    function getUserIdSet($userSet) {
        $userIds = [];
        foreach($userSet as $user) {
            $userIds[] = $user->id;
        }
        return $userIds;
    }
    function getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet, $allTaskCompetencesIds) {
        $suitableUserSubsets = [];
        foreach ($allSubsetsOfMyUserSet as $userSubset) {
            if ($this::isSubsetAcceptable($userSubset, $allTaskCompetencesIds)) {
                $suitableUserSubsets[] = collect($userSubset);
                    //$this::getUserIdSet($userSubset);
            }
        }
        return $suitableUserSubsets;
    }
    public function suitableAssigneesSets()
    {
        $allCompetenceLevels = ["Básico", "Intermediário", "Avançado"];
        $myUserSet = [];
        $allTaskCompetencesIdsAndLevels = [];
        $taskCompetences = $this->competencies;
        foreach($taskCompetences as $taskCompetence) {
            $taskRequiredCompetenceLevel = $taskCompetence->pivot->competency_level;
            $acceptableCompetenceLevels = $allCompetenceLevels;
            if (in_array($taskRequiredCompetenceLevel, $allCompetenceLevels)) {
                $start = array_search($taskRequiredCompetenceLevel, $allCompetenceLevels);
                $acceptableCompetenceLevels = array_slice($allCompetenceLevels, $start);
            }
            $allTaskCompetencesIdsAndLevels[$taskCompetence->id] = $acceptableCompetenceLevels;
            $usersThatHaveTheCompetenceInAnAcceptableLevel = $taskCompetence->skilledUsers()->wherePivotIn('competency_level', $acceptableCompetenceLevels)->get();
            if ($usersThatHaveTheCompetenceInAnAcceptableLevel->isEmpty()) {
                //echo "Nao há um usuário com essa competencia no nivel aceitavel - $taskCompetence->name - $taskCompetence->id $taskRequiredCompetenceLevel <br>";
                return [];
            } else {

               // echo "tem algum usuario - $taskCompetence->name $taskCompetence->id - $taskRequiredCompetenceLevel <br> ";
                //echo count($usersThatHaveTheCompetenceInAnAcceptableLevel);
                foreach($usersThatHaveTheCompetenceInAnAcceptableLevel as $user) {
                    if (!array_key_exists($user->id, $myUserSet)) {
                        $myUserSet[$user->id] = $user;
                    }
                }

            }
        }
        if (count($allTaskCompetencesIdsAndLevels) == 0) {
            echo "nao tem suitable assignees";
            return [];
        }
        $newArray = [];
        foreach($myUserSet as $user) {
            $newArray[] = $user;
        }
        //no final myUserSet vai ter todos os usuarios que tem alguma competencia da task num nivel aceitavel.
        // gerar todos os subsets desse set e ver se os usuarios tem todas as competencias

        $allSubsetsOfMyUserSet = $this::powerSet($newArray,1);
        //echo "<br> oakoskaksokaksoaksokaoskoakoskoa <br>";
        //echo count($allSubsetsOfMyUserSet);

        $suitableAssigneesIdsSet = $this::getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet,$allTaskCompetencesIdsAndLevels);
        //echo "<br> <br>";
        //var_dump($allSubsetsOfMyUserSet);
        //echo "suitable results <br>:";
        //print_r($suitableAssigneesIdsSet);
        return $suitableAssigneesIdsSet;
        //$paginator = new Paginator($items->forPage($page,$per_page),$items->count(),$per_page,$page);
        //return this::getRelationList($suitableAssigneesIdsSet, $taskCompetences);

    }

    public function getRelationList($suitableAssigneesIdsSet, $taskCompetences) {

    }

    public function suitableTeams()
    {
        //TODO
        return $this->belongsToMany('App\Competency', 'task_competencies')
            ->withPivot('competency_level');
    }
}
