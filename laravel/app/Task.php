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
            ->withPivot('competency_proficiency_level_id');
    }
	
	// TODO -> Retirar este método e permitir apenas o novo método na nova modelagem (Task x Users, sem Teams, como era antes) quando for fazer o merge
	public function members()
	{
		return \App\User::all();
		//return $this->belongsToMany('App\User', 'tasks_users');
	}
	
	public function answers()
	{
		return DB::table('answers')->where("task_id", $this->id)->get();
		//return $this->belongsToMany('App\User', 'tasks_users');
	}
	
	public function usersWhoAnsweredQuestions()
	{
		$judges_id = DB::table('answers')->select('judge_user_id')->where([ ['judge_user_id', '<>', null], ['task_id', '=', $this->id] ])->get();
		return $judges_id;
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
		return [];
        $allCompetenceLevels = CompetenceProficiencyLevel::all()->pluck('id')->toArray();
        $myUserSet = [];
        $allTaskCompetencesIdsAndLevels = [];
        $taskCompetences = $this->competencies;
        foreach ($taskCompetences as $taskCompetence) {
            $taskRequiredCompetenceLevel = $taskCompetence->pivot->competency_proficiency_level_id;
            $acceptableCompetenceLevels = $allCompetenceLevels;
            if (in_array($taskRequiredCompetenceLevel, $allCompetenceLevels)) {
                $start = array_search($taskRequiredCompetenceLevel, $allCompetenceLevels);
                $acceptableCompetenceLevels = array_slice($allCompetenceLevels, $start);
            }
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
	
	// TODO: adicionar mais um status: finalizado-pendente, quando ainda faltam pessoas para preencher o form
	// e "finalizado", que realmente simboliza o finalizado
	public function taskStatus() {
		$start_date_original = $this->getOriginal('start_date');
		$end_date_original = $this->getOriginal('end_date');
		$date_null = null;
		if ($start_date_original == $date_null) {
			return "created";
		} elseif ($start_date_original != $date_null and $end_date_original == $date_null ) {
			return "initialized";
		} elseif ($start_date_original != $date_null and $end_date_original != $date_null ) {
			return "finished";
		}
	}
	
}
