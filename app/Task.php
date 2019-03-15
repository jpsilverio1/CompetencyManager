<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description',
    ];

    private $debugEnabled = False;

    const NUMBER_OF_COMPETENCIES = "number of competencies";
    const NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL = "number of competencies in acceptable level";
    const REMBEMRING_LEVEL = "remembering level";
    const NUMBER_OF_ENDORSEMENTS = "number of endorsements";
    const COLLABORATIVE_COMPETENCIES = "collaborative competencies";
    const PARAMETER_PRIORITIZATION_MAP = array(self::COLLABORATIVE_COMPETENCIES => TRUE,
        self::NUMBER_OF_COMPETENCIES => TRUE,
        self::NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL => TRUE,
        self::REMBEMRING_LEVEL => TRUE,
        self::NUMBER_OF_ENDORSEMENTS => TRUE);
    const PRIOTIRY_PARAMTER_ATTRIBUTE_VALUE_FUNCTION = array(self::COLLABORATIVE_COMPETENCIES => 'candidateCollaborativeCompetencies',
        self::NUMBER_OF_COMPETENCIES => 'candidateNumberOfCompetenciesRank',
        self::NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL => 'candidateNumberOfCompetenciesInAcceptableLevel',
        self::REMBEMRING_LEVEL => 'candidateRememberingLevel',
        self::NUMBER_OF_ENDORSEMENTS => 'candidateNumberOfEndorsements');

    const PARAMATER_SORT_FUNCTION_MAP = array(self::COLLABORATIVE_COMPETENCIES => 'getRankBiggerAtributtesFirst',
        self::NUMBER_OF_COMPETENCIES => 'getRankBiggerAtributtesFirst',
        self::NUMBER_OF_COMPETENCES_IN_ACCEPTABLE_LEVEL => 'getRankBiggerAtributtesFirst',
        self::REMBEMRING_LEVEL => 'getRankBiggerAtributtesFirst',
        self::NUMBER_OF_ENDORSEMENTS => 'getRankBiggerAtributtesFirst');

    public function getFinalRankAndExplanations() {
        $taskCandidatesInfo = $this->allCandidates();
        $finalRanks = [];
        $individualRanks = [];
        $individualAtributteValues = [];
        $candidates = $taskCandidatesInfo["candidates"];
        $outroInfo = [];
        $another = [];

        if (count($candidates) < 1) {
            return ["candidates" => [], "novaRecomendacao" => ["finalResult" => [], "candidates" => [], "candidatesContribution" => []]];
        }
        foreach($candidates as $candidate) {
            $finalRanks[$candidate->id] = 0;
        }
        foreach(self::PARAMETER_PRIORITIZATION_MAP as $paramater => $useParameter) {
            if ($useParameter) {
                foreach($candidates as $candidate) {
                    $valueCalculationFunction = self::PRIOTIRY_PARAMTER_ATTRIBUTE_VALUE_FUNCTION[$paramater];
                    $info = $this->$valueCalculationFunction($taskCandidatesInfo, $candidate);
                    $individualAtributteValues[$paramater][$candidate->id] = $info["finalValue"];
                    foreach($info["individualTaskValues"] as $competenceId => $individualValue) {
                        $outroInfo[$candidate->id][$competenceId][$paramater] = $individualValue;
                    }

                    $another[$candidate->id][$paramater] =  $info["individualCandidateValues"];
                }
                $ola = self::PARAMATER_SORT_FUNCTION_MAP[$paramater];
                $individualRanks[$paramater] = $this->$ola($individualAtributteValues[$paramater]);
                foreach($candidates as $candidate) {
                    $finalRanks[$candidate->id] = $finalRanks[$candidate->id] + $individualRanks[$paramater][$candidate->id];
                }
            }

        }
        if ($this->debugEnabled) {
            echo " final individual values: <br>";
            print_r($individualAtributteValues);
            echo "final ranks: <br>";
            print_r($individualRanks);
        }
        $fullInfo = [];
        asort($finalRanks);
        $order =1;
        foreach ($finalRanks as $candidateId => $finalRank) {
            $fullInfo["candidates"][] =  $taskCandidatesInfo["candidates"][$candidateId];
            $fullInfo["ranking"][$candidateId] = $finalRank;
            $fullInfo["candidateRanking"][$candidateId] = $order;


            // data by priority parameter and user

            $fullInfo["individualCandidateValues"] = $another;
        // TODO - > finish and display the collaborative things in view

            $order = $order + 1;
        }
        $fullInfo["rankingData"]["details"] = $outroInfo;
        $fullInfo["candidatesContribution"] = $taskCandidatesInfo["candidatesContribution"];
        $fullInfo["novaRecomendacao"] = $this->taskTeamRecommendations($candidates, $taskCandidatesInfo["candidatesContribution"]);
        $fullInfo["rankingData"]["individualRankingValues"] = $individualAtributteValues;
        return $fullInfo;
    }


    public function userCompetencesThatCoverTaskCompetenceInAcceptableLevel($taskCompetenceId, $competenceInfo) {
        $userCompetencesInAcceptableLevel = [];
        foreach($competenceInfo[$taskCompetenceId]["acceptableLevel"] as $userCompetenceId =>$isLevelAcceptable) {
            if ($isLevelAcceptable) {
                //alguma competence em nivel aceitavel
                $userCompetencesInAcceptableLevel[] = $userCompetenceId;
            }
        }
        return $userCompetencesInAcceptableLevel;
    }


    public function taskTeamRecommendations($candidates, $candidatesContribution)
    {

        $taskCompetenciesCoveredByCandidates = [];
        $keepCand = [];

        $suitableCandidates = [];
        $suitableCandidatesContribution = [];

        foreach($candidatesContribution as $candidateId => $data) {
            foreach($data["competenceRep"] as $taskCompetenceId) {
                $userCompetencesThatCoverTaskCompetenceInAcceptableLevel = $this->userCompetencesThatCoverTaskCompetenceInAcceptableLevel($taskCompetenceId, $data["competenceInfo"]);
                if (count($userCompetencesThatCoverTaskCompetenceInAcceptableLevel)>0) {
                    $taskCompetenciesCoveredByCandidates[$taskCompetenceId] = $taskCompetenceId;
                    $keepCand[$candidateId] = $candidateId;
                    $suitableCandidatesContribution[$candidateId]["competenceRep"][] = $taskCompetenceId;
                    $suitableCandidatesContribution[$candidateId]["competenceInfo"][$taskCompetenceId]["competence"] = $userCompetencesThatCoverTaskCompetenceInAcceptableLevel;
                }
            }
        }
        $taskCompetenciesIds = $this->competencies()->pluck('competencies.id')->toArray();
        if ($this->debugEnabled) {
            echo "satisfied task competencies: ";
            print_r($taskCompetenciesCoveredByCandidates);
            echo "<br> suitable candidates: ";
            print_r($keepCand);
            echo "<br> tarefa: ";
            print_r($taskCompetenciesIds);
        }

        $containsAllValues = !array_diff($taskCompetenciesIds, $taskCompetenciesCoveredByCandidates);
        if(!$containsAllValues) {
            //there is no user that has the competency in an acceptable level
            return ["finalResult" => [], "candidates" => [], "candidatesContribution" => []];
        }

        $allTaskCompetencesIdsAndLevels = [];
        $taskCompetences = $this->competencies;
        foreach ($taskCompetences as $taskCompetence) {
            $allTaskCompetencesIdsAndLevels[$taskCompetence->id] = $taskCompetence->getAcceptableLevelsFromTaskCompetence();
        }

        if (count($allTaskCompetencesIdsAndLevels) == 0) {
            return ["finalResult" => [], "candidates" => [], "candidatesContribution" => []];
        }
        $newArray = [];
        foreach ($keepCand as $userId) {
            $newArray[] = $candidates[$userId];
            $suitableCandidates[$userId] = $candidates[$userId];
        }
        //no final myUserSet vai ter todos os usuarios que tem alguma competencia da task num nivel aceitavel.
        // gerar todos os subsets desse set e ver se os usuarios tem todas as competencias

        $allSubsetsOfMyUserSet = $this::powerSet($newArray, 1);
        $suitableAssigneesIdsSet = $this::getSuitableAssigneesFromSubset($allSubsetsOfMyUserSet, $allTaskCompetencesIdsAndLevels);
        return ["finalResult" => $this::filterSets($suitableAssigneesIdsSet), "candidates" => $suitableCandidates, "candidatesContribution" => $suitableCandidatesContribution];
        //return $this::filterSets($suitableAssigneesIdsSet);

    }
    public function isFeasible() {
        foreach($this->competencies as $taskCompetence) {
            $descendantsAndSelfIds = Competency::descendantsAndSelf($taskCompetence->id)->pluck('id');
            $countUser = \DB::table('user_competences')
                ->whereIn('competence_id', $descendantsAndSelfIds)
                ->count();
            $countLearning = \DB::table('learning_aids_competencies')
                ->whereIn('competency_id', $descendantsAndSelfIds)
                ->count();
            $totalCount = $countLearning + $countUser;
            if ($totalCount == 0) {
               return False;
            }
        }
        return True;
    }

    public function allCandidates() {
        $candidates = [];
        $candidateContribution = [];
        foreach($this->competencies as $taskCompetence) {
            foreach($taskCompetence->skilledUsersConsideringSubCategories() as $userId=>$userData) {
                $skilledUser = $userData["user"];
                $candidates[$userId] = $skilledUser;
                $candidateContribution[$skilledUser->id]["competenceRep"][] = $taskCompetence->id;
                foreach($userData["competences"] as $competence) {
                    $userCompetence = $skilledUser->competences()->where("competence_id", $competence->id)->first();
                    $candidateContribution[$skilledUser->id]["competenceInfo"][$taskCompetence->id]["competence"][] = $userCompetence;
                    $candidateContribution[$skilledUser->id]["competenceInfo"][$taskCompetence->id]["acceptableLevel"][$userCompetence->id] = $taskCompetence->isCompetenceLevelAcceptable($userCompetence);
                }
            }
        }

        if ($this->debugEnabled) {
            foreach ($candidateContribution as $userId => $userContribution) {
                $user = $candidates[$userId];
                echo "--------Usuario: $user->name <br>";
                foreach($this->competencies as $taskCompetence) {
                    echo "\t $taskCompetence->name - $taskCompetence->id: <br>";
                    if (array_key_exists($taskCompetence->id, $userContribution["competenceInfo"])) {
                        foreach($userContribution["competenceInfo"][$taskCompetence->id]["competence"] as $index => $userCompetence) {
                            $acceptableLevelKeys = array_keys($userContribution["competenceInfo"][$taskCompetence->id]["acceptableLevel"]);
                            if ($userContribution["competenceInfo"][$taskCompetence->id]["acceptableLevel"][$acceptableLevelKeys[$index]]) {
                                echo "\t\t $userCompetence->name -> nível aceitavel <br>";
                            } else {
                                echo "\t\t $userCompetence->name -> nivel inaceitavel <br>";
                            }
                        }
                    } else {
                        echo "nao cobre <br>";
                    }
                }
            }
        }

        return ["candidates" => $candidates, "candidatesContribution" => $candidateContribution];
    }

    function candidateNumberOfCompetenciesRank($taskCandidatesInfo, $candidate) {
        $numberOfCompetencies = count($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"]);
        if ($this->debugEnabled) {
            echo "$candidate->name - count number of competencies: $numberOfCompetencies <br>";
        }
        return ["finalValue" => $numberOfCompetencies, "individualTaskValues" => [], "individualCandidateValues" => []];
    }

    function candidateNumberOfCompetenciesInAcceptableLevel($taskCandidatesInfo, $candidate) {
        $rank =0;
        $individualTaskValues = [];
        $individualCandidateValues = [];
        foreach($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"] as $taskCompetenceId => $data) {
            foreach($data["acceptableLevel"] as $candidateCompetenceId=>$competenceInAcceptableLevel) {
                $individualCandidateValues[$candidateCompetenceId] = "false";
                if ($competenceInAcceptableLevel) {
                    $individualCandidateValues[$candidateCompetenceId] = "true";
                }
            }
        }
        foreach($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"] as $taskCompetenceId => $data) {
            $individualTaskValues[$taskCompetenceId] = "false";
            foreach($data["acceptableLevel"] as $candidateCompetenceId=>$competenceInAcceptableLevel) {
                if ($competenceInAcceptableLevel) {
                    $rank = $rank + 1;
                    $individualTaskValues[$taskCompetenceId] = "true";
                    break;
                }
            }
        }
        if ($this->debugEnabled) {
            echo "$candidate->name - count number of competencies in acceptable level: $rank individual values: ";
            print_r($individualTaskValues);
            echo "<br>";
        }

        return ["finalValue" => $rank, "individualTaskValues" =>$individualTaskValues, "individualCandidateValues" => $individualCandidateValues];
    }
    function candidateNumberOfEndorsements($taskCandidatesInfo, $candidate) {
        $rank = 0;
        $total = $this::candidateNumberOfCompetenciesRank($taskCandidatesInfo, $candidate)["finalValue"];
        $individualTaskValues = [];

        if ($total == 0) {
            return ["finalValue" => 0, "individualTaskValues" => [], "individualCandidateValues" => []];
        }
        $individualCandidateValues = [];
        foreach($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"] as $taskCompetenceId => $data) {
            $numberOfEndorsements = [];
            foreach($data["competence"] as $candidateCompetence) {
                $numberOfEndorsementsVal = $candidate->getNumberOfEndorsementsForCompetence($candidate->endorsements(),$candidateCompetence);
                $numberOfEndorsements[] = $numberOfEndorsementsVal;
                $individualCandidateValues[$candidateCompetence->id] = $numberOfEndorsementsVal;
            }
            $maxNumberOfEndorsements = max($numberOfEndorsements);
            $rank = $rank + $maxNumberOfEndorsements;
            $individualTaskValues[$taskCompetenceId] = $maxNumberOfEndorsements;
            if ($this->debugEnabled) {
                echo "candidato: $candidate->name - max number: $maxNumberOfEndorsements -> number of endorsements array: ";
                print_r($numberOfEndorsements);
                echo "<br>";
            }
        }
        if ($this->debugEnabled) {
            echo "$candidate->name - final number of endorsements: $rank individual values: ";
            print_r($individualTaskValues);
            echo "<br>";
        }

        return ["finalValue" => ($rank/$total), "individualTaskValues" => $individualTaskValues, "individualCandidateValues" => $individualCandidateValues];
    }
    function candidateRememberingLevel($taskCandidatesInfo, $candidate) {
        $rank = 0;
        $total = $this::candidateNumberOfCompetenciesRank($taskCandidatesInfo, $candidate)["finalValue"];
        $individualTaskValues = [];
        if ($total == 0) {
            return ["finalValue" => 0, "individualTaskValues" => [], "individualCandidateValues" => []];
        }
        $individualCandidateValues = [];
        foreach($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"] as $taskCompetenceId => $data) {
            $rememberingLevels = [];
            foreach($data["competence"] as $candidateCompetence) {
                $rememberingLevel = $candidate->forgettingLevel($candidateCompetence);
                $rememberingLevels[] = $rememberingLevel;
                $individualCandidateValues[$candidateCompetence->id] = $rememberingLevel;
            }
            $maxRememberingLevel = max($rememberingLevels);
            $rank = $rank + $maxRememberingLevel;
            $individualTaskValues[$taskCompetenceId] = $maxRememberingLevel;
            if ($this->debugEnabled) {
                echo "candidato: $candidate->name - max number: $maxRememberingLevel -> remembering levels array: ";
                print_r($rememberingLevels);
                echo "<br>";
            }

        }
        if ($this->debugEnabled) {
            echo "$candidate->name - final remembering level: $rank individual values: ";
            print_r($individualTaskValues);
            echo "<br>";
        }

        return ["finalValue" => ($rank/$total), "individualTaskValues" => $individualTaskValues, "individualCandidateValues" => $individualCandidateValues];
    }
    function candidateCollaborativeCompetencies($taskCandidatesInfo, $candidate) {
        $personal_competence_level_id_min = \DB::table('personal_competence_proficiency_levels')->min('id');
        $personal_competence_level_id_max = \DB::table('personal_competence_proficiency_levels')->max('id');
        if ($personal_competence_level_id_max == $personal_competence_level_id_min) {
            return ["finalValue" => 0, "individualTaskValues" => [], "individualCandidateValues" => []];
        }
        $average_collaboration_level = $candidate->averageCollaborationLevel();
        if ($average_collaboration_level == null) {
           $average_collaboration_level = 0;
        }
        return ["finalValue" => $average_collaboration_level, "individualTaskValues" => [], "individualCandidateValues" => []];
    }

    function sortAndReturnCandidateRanks($candidateAtributteValueArray, $sortInAscendingOrder) {
        $epsilon = 0.00001;
        if ($sortInAscendingOrder) {
            asort($candidateAtributteValueArray);
        } else {
            arsort($candidateAtributteValueArray);
        }
        $currentRank = 1;
        $finalRanks = [];
        $candidateAtributteValueArrayKeys = array_keys($candidateAtributteValueArray);
        for ($i = 0; $i <(count($candidateAtributteValueArrayKeys) -1); $i++) {
            $currentCandidateId = $candidateAtributteValueArrayKeys[$i];
            $finalRanks[$currentCandidateId] = $currentRank;
            $nextCandidateId = $candidateAtributteValueArrayKeys[$i+1];
            if(abs(($candidateAtributteValueArray[$currentCandidateId]-$candidateAtributteValueArray[$nextCandidateId])) >= $epsilon) {
                $currentRank = $currentRank + 1;
            }
        }
        $finalRanks[$candidateAtributteValueArrayKeys[$i]] = $currentRank;
        if ($this->debugEnabled) {
            echo "<br> initial array: <br>";
            print_r($candidateAtributteValueArray);
            echo  "<br> final rank array: <br>";
            print_r($finalRanks);
        }

        return $finalRanks;
    }

    function getRankBiggerAtributtesFirst($candidateAtributteValueArray) {
        return $this::sortAndReturnCandidateRanks($candidateAtributteValueArray, FALSE);
    }

    function getRankBiggerAtributtesLast($candidateAtributteValueArray) {
        return $this::sortAndReturnCandidateRanks($candidateAtributteValueArray, TRUE);
    }

    public function competencies(){
        return $this->belongsToMany('App\Competency','task_competencies','task_id','competency_id')
            ->withPivot('competency_proficiency_level_id')
            ->join('competence_proficiency_level','competency_proficiency_level_id','=','competence_proficiency_level.id')
            ->select('competencies.*', 'competence_proficiency_level.name as pivot_proficiency_level_name');
    }

	public function answers()
	{
		return DB::table('answers')->where("task_id", $this->id)->get();
	}

	public function usersWhoAnsweredQuestions()
	{
		$judges_id = DB::table('answers')->select('judge_user_id')->where([ ['judge_user_id', '<>', null], ['task_id', '=', $this->id] ])->get();
		return $judges_id;
	}

    public function teamMembers() {
        return $this->belongsToMany('App\User', 'task_teams', 'task_id', 'task_team_member_id');
    }

    public function hasTeamAssigned() {
        return count($this->teamMembers) > 0;
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

    public function oldTaskTeamRecommendations()
    {
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();
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
        echo " old method: ";
        print_r(array_keys($myUserSet));
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

	public function taskStatus() {
		$start_date_original = $this->getOriginal('start_date');
		$end_date_original = $this->getOriginal('end_date');
		$date_null = null;

		if ($start_date_original == $date_null) {
		    if ($this->hasTeamAssigned()) {
		        return "teamAssigned";
            }
			return "created";
		} elseif ($start_date_original != $date_null and $end_date_original == $date_null ) {
			return "initialized";
		} elseif ($start_date_original != $date_null and $end_date_original != $date_null ) {
			return "finished";
		}
	}

	public function canBeInitialized() {
        $start_date_original = $this->getOriginal('start_date');
        return (($start_date_original == null) && $this->hasTeamAssigned());
    }

    public function canHaveTeamAssigned() {
        return ($this->taskStatus() != "finished");
    }

}
