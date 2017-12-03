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

    const NUMBER_OF_COMPETENCIES = "number of competencies";
    const NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL = "number of competencies in acceptable level";
    const REMBEMRING_LEVEL = "remembering level";
    const NUMBER_OF_ENDORSEMENTS = "number of endorsements";
    const COLLABORATIVE_COMPETENCIES = "collaborative competencies";
    const PARAMETER_PRIORITIZATION_MAP = array(self::COLLABORATIVE_COMPETENCIES => FALSE,
        self::NUMBER_OF_COMPETENCIES => TRUE,
        self::NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL => TRUE,
        self::REMBEMRING_LEVEL => FALSE,
        self::NUMBER_OF_ENDORSEMENTS => TRUE);
    const PRIOTIRY_PARAMTER_ATTRIBUTE_VALUE_FUNCTION = array(self::COLLABORATIVE_COMPETENCIES => 'candidateCollaborativeCompetencies',
        self::NUMBER_OF_COMPETENCIES => 'candidateNumberOfCompetenciesRank',
        self::NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL => 'candidateNumberOfCompetenciesInAcceptableLevel',
        self::REMBEMRING_LEVEL => 'candidateRememberingLevel',
        self::NUMBER_OF_ENDORSEMENTS => 'candidateNumberOfEndorsements');

    const PARAMATER_SORT_FUNCTION_MAP = array(self::COLLABORATIVE_COMPETENCIES => 'getRankBiggerAtributtesFirst',
        self::NUMBER_OF_COMPETENCIES => 'getRankBiggerAtributtesFirst',
        self::NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL => 'getRankBiggerAtributtesFirst',
        self::REMBEMRING_LEVEL => 'getRankBiggerAtributtesLast',
        self::NUMBER_OF_ENDORSEMENTS => 'getRankBiggerAtributtesFirst');

    public function getFinalRankAndExplanations() {
        $taskCandidatesInfo = $this->allCandidates();
        $finalRanks = [];
        $individualRanks = [];
        $individualAtributteValues = [];
        $candidates = $taskCandidatesInfo["candidates"];
        foreach($candidates as $candidate) {
            $finalRanks[$candidate->id] = 0;
        }
        foreach(self::PARAMETER_PRIORITIZATION_MAP as $paramater => $useParameter) {
            if ($useParameter) {
                foreach($candidates as $candidate) {
                    $oi = self::PRIOTIRY_PARAMTER_ATTRIBUTE_VALUE_FUNCTION[$paramater];
                    $individualAtributteValues[$paramater][$candidate->id] = $this->$oi($taskCandidatesInfo, $candidate);
                }
                $ola = self::PARAMATER_SORT_FUNCTION_MAP[$paramater];
                $individualRanks[$paramater] = $this->$ola($individualAtributteValues[$paramater]);
                foreach($candidates as $candidate) {
                    $finalRanks[$candidate->id] = $finalRanks[$candidate->id] + $individualRanks[$paramater][$candidate->id];
                }
            }

        }
        $fullInfo = [];
        asort($finalRanks);
        foreach ($finalRanks as $candidateId => $finalRank) {
            $fullInfo["candidates"][] =  $taskCandidatesInfo["candidates"][$candidateId];
            $fullInfo["ranking"][$candidateId] = $finalRank;
            $fullInfo["candidatesContribution"] = $taskCandidatesInfo["candidatesContribution"];
        }
        return $fullInfo;
    }

    public function allCandidates() {
        $total = [];
        $candidateContribution = [];
        foreach($this->competencies as $taskCompetence) {
            foreach($taskCompetence->skilledUsers as $skilledUser) {
                $total[$skilledUser->id] = $skilledUser;
                if (array_key_exists($skilledUser->id, $candidateContribution)) {
                    $candidateContribution[$skilledUser->id]["competenceInfo"]["competence"][] = $taskCompetence;
                    $candidateContribution[$skilledUser->id]["competenceInfo"]["acceptableLevel"][] = $taskCompetence->isCompetenceLevelAcceptable($skilledUser->competences()->where("competence_id", $taskCompetence->id)->first());
                } else {
                    $candidateContribution[$skilledUser->id]["competenceInfo"]["competence"] = [$taskCompetence];
                    $candidateContribution[$skilledUser->id]["competenceInfo"]["acceptableLevel"] = [$taskCompetence->isCompetenceLevelAcceptable($skilledUser->competences()->where("competence_id", $taskCompetence->id)->first())];
                }
            }
        }
        return ["candidates" => $total, "candidatesContribution" => $candidateContribution];
    }
    function candidateNumberOfCompetenciesRank($taskCandidatesInfo, $candidate) {
        return count($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"]["competence"]);
    }

    function candidateNumberOfCompetenciesInAcceptableLevel($taskCandidatesInfo, $candidate) {
        $rank =0;
        foreach ($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"]["acceptableLevel"] as $levelIsAcceptable) {
            if ($levelIsAcceptable) {
                $rank = $rank + 1;
            }
        }
        return $rank;
    }

    function candidateNumberOfEndorsements($taskCandidatesInfo, $candidate) {
        $rank = 0;
        $total = $this::candidateNumberOfCompetenciesRank($taskCandidatesInfo, $candidate);
        if ($total == 0) {
            return 0;
        }
        foreach($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"]["competence"] as $candidateCompetence) {
            $rank = $rank + $candidate->getNumberOfEndorsementsForCompetence($candidate->endorsements(),$candidateCompetence);
        }
        return $rank/$total;
    }

    function sortAndReturnCandidateRanks($candidateAtributteValueArray, $sortInAscendingOrder) {
        if ($sortInAscendingOrder) {
            asort($candidateAtributteValueArray);
        } else {
            arsort($candidateAtributteValueArray);
        }
        $currentRank = 1;
        $finalRanks = [];
        foreach ($candidateAtributteValueArray as $candidateId => $val) {
            $finalRanks[$candidateId] = $currentRank;
            $currentRank = $currentRank + 1;
        }
        return $finalRanks;
    }

    function getRankBiggerAtributtesFirst($candidateAtributteValueArray) {
        return $this::sortAndReturnCandidateRanks($candidateAtributteValueArray, FALSE);
    }

    function getRankBiggerAtributtesLast($candidateAtributteValueArray) {
        return $this::sortAndReturnCandidateRanks($candidateAtributteValueArray, TRUE);
    }




    public function competencies()
    {
        return $this->belongsToMany('App\Competency', 'task_competencies')
            ->withPivot('competency_proficiency_level_id');
    }

    public function teamMembers() {
        return $this->belongsToMany('App\User', 'task_teams', 'task_id', 'task_team_member_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User');
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

    public function suitableAssigneesSets()
    {

        $myUserSet = [];
        $allTaskCompetencesIdsAndLevels = [];
        $taskCompetences = $this->competencies;
        foreach ($taskCompetences as $taskCompetence) {
            $acceptableCompetenceLevels = $taskCompetence->getAcceptableLevelsFromTaskCompetence();
            $allTaskCompetencesIdsAndLevels[$taskCompetence->id] = $acceptableCompetenceLevels;
            $usersThatHaveTheCompetenceInAnAcceptableLevel = $taskCompetence->
            skilledUsersConsideringSubCategoriesAndCompetenceLevels($acceptableCompetenceLevels);
            if ($usersThatHaveTheCompetenceInAnAcceptableLevel->isEmpty()) {
                //there is no user that has the competency in an acceptable level
                return [];
            } else {
                foreach ($usersThatHaveTheCompetenceInAnAcceptableLevel as $user) {
                    if (!array_key_exists($user->id, $myUserSet)) {
                        $myUserSet[$user->id] = $user;
                    }
                }
            }
        }
        if (count($allTaskCompetencesIdsAndLevels) == 0) {
            return [];
        }
        $newArray = [];
        foreach ($myUserSet as $user) {
            $newArray[] = $user;
        }
        //no final myUserSet vai ter todos os usuarios que tem alguma competencia da task num nivel aceitavel.
        // gerar todos os subsets desse set e ver se os usuarios tem todas as competencias

        $allSubsetsOfMyUserSet = $this::powerSet($newArray, 1);
        $suitableAssigneesIdsSet = $this::getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet, $allTaskCompetencesIdsAndLevels);
        return $this::filterSets($suitableAssigneesIdsSet);
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
