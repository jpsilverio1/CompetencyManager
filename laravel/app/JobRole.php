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
        return $this->belongsToMany('App\Competency', 'jobroles_competencies', 'jobrole_id', 'competency_id')->withPivot('competence_proficiency_level_id');
    }

    private function powerSet($in, $minLength = 1)
    {
        $count = count($in);
        $members = pow(2, $count);
        $return = array();
        for ($i = 0; $i < $members; $i++) {
            $b = sprintf("%0" . $count . "b", $i);
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

    function isSubsetAcceptable($userSubset, $allTaskCompetencesIds)
    {
        foreach ($allTaskCompetencesIds as $competenceId => $competenceAcceptableLevels) {
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

    function getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet, $allTaskCompetencesIds)
    {
        $suitableUserSubsets = [];
        foreach ($allSubsetsOfMyUserSet as $userSubset) {
            if ($this::isSubsetAcceptable($userSubset, $allTaskCompetencesIds)) {
                $suitableUserSubsets[] = $userSubset;
            }
        }
        return $suitableUserSubsets;
    }

    // Esse é o método chamado na view
    // Objetivo: recomendar USERS para JOBROLES baseado em COMPETENCIES
    // (antes aqui era recomendar users para tasks baseado em competencies)
    public function suitableAssigneesSets()
    {
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();
        $myUserSet = [];
        $allJobRoleCompetencesIdsAndLevels = [];

        $jobRoleCompetences = $this->competencies;

        foreach ($jobRoleCompetences as $jobRoleCompetence) {
            $jobRoleRequiredCompetenceLevel = $jobRoleCompetence->pivot->competence_proficiency_level_id;
            $acceptableCompetenceLevels = $allCompetenceLevels;
            if (in_array($jobRoleRequiredCompetenceLevel, $allCompetenceLevels)) {
                $start = array_search($jobRoleRequiredCompetenceLevel, $allCompetenceLevels);
                $acceptableCompetenceLevels = array_slice($allCompetenceLevels, $start);
            }
            $allJobRoleCompetencesIdsAndLevels[$jobRoleCompetence->id] = $acceptableCompetenceLevels;
            $usersThatHaveTheCompetenceInAnAcceptableLevel = $jobRoleCompetence->
            skilledUsersConsideringSubCategoriesAndCompetenceLevels($acceptableCompetenceLevels);
            if ($usersThatHaveTheCompetenceInAnAcceptableLevel->isEmpty()) {
                //there is no user that has the competency in an acceptable level
                return [];
            } else {
                $myUserSetCount = [];
                foreach ($usersThatHaveTheCompetenceInAnAcceptableLevel as $user) {
                    if (!array_key_exists($user->id, $myUserSet)) {
                        $myUserSet[$user->id] = $user;
                        $myUserSetCount[$user->id] += 1;
                    }
                }
            }
        }
        if (count($allJobRoleCompetencesIdsAndLevels) == 0) {
            return [];
        }

        return [];
    }

    public function filterSets($suitableAssigneesIdsSet)
    {
        $result = $suitableAssigneesIdsSet;
        $flags = [];
        foreach ($suitableAssigneesIdsSet as $oi) {
            $flags[] = True;
        }
        for ($i = 0; $i < sizeOf($suitableAssigneesIdsSet); $i++) {
            for ($j = $i + 1; $j < sizeOf($suitableAssigneesIdsSet); $j++) {
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
        $result = [];
        for ($i = 0; $i < sizeOf($suitableAssigneesIdsSet); $i++) {
            if ($flags[$i]) {
                $result[] = $suitableAssigneesIdsSet[$i];
            }
        }
        return $result;

    }


}
