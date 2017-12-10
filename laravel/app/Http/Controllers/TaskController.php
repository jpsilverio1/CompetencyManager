<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateTaskFormRequest;
use App\Http\Requests\EditTaskFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use App\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTasks = Task::paginate(10);
        return view('tasks.index', ['tasks' => $allTasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new \App\Task;
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTaskFormRequest $request)
    {
		$title = $request->get('title');
		$description = $request->get('description');
		$author_id = \Auth::user()->id;

        $task = new \App\Task;
        $task->title = $title;
        $task->description = $description;
        $task->author_id = $author_id;
        $task->save();

        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $task->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $task->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $task->competencies()->updateExistingPivot($competenceId, ['competency_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('tasks.show',$task->id)->withMessage('A tarefa foi cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $message = null)
    {
        $task = Task::findOrFail($id);
		return view('tasks.show', ['task' => $task, 'message' => $message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
		return view('tasks.edit', ['task' => $task]);
    }

    public function storeTaskTeam(Request $request) {
        $taskId = $request->get('task_id');
        $teamMembersIds = $request->get('team_member_id');
        $task = Task::findOrFail($taskId);
        foreach ($teamMembersIds as $teamMemberId) {
            $results = $task->teamMembers()->where('task_team_member_id', '=', $teamMemberId)->get();
            if ($results->isEmpty()) {
                $task->teamMembers()->attach($teamMemberId);
            }
        }
        return Redirect::route('tasks.show',$task->id)->withMessage('A equipe foi cadastrada com sucesso!');


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditTaskFormRequest $request, $id)
    {
        $title = $request->get('title');
		$description = $request->get('description');
        $task = Task::findOrFail($id);

        $task->title = $title;
        $task->description = $description;
        $task->save();
        $competenceIds = $request->get('competence_ids');
        $competenceProficiencyLevels = $request->get('competency_proficiency_levels');
        for ($i=0; $i<sizeOf($competenceIds); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceProficiencyLevel = $competenceProficiencyLevels[$i];
            $results = $task->competencies()->where('competency_id', '=', $competenceId)->get();
            if ($results->isEmpty()) {
                //add competency
                $task->competencies()->attach([$competenceId => ['competency_proficiency_level_id'=>$competenceProficiencyLevel]]);
            } else {
                //update competency level
                $task->competencies()->updateExistingPivot($competenceId, ['competency_proficiency_level_id'=>$competenceProficiencyLevel]);
            }
        }
        return Redirect::route('tasks.show',$task->id)->withMessage('A tarefa foi atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
		$task->competencies()->detach();
		$task->delete();

        return Redirect::route('tasks.index')->withMessage('A tarefa foi excluída com sucesso!');

	}

	public function deleteCompetencyFromTask($taskId, $competencyId) {
        $task = Task::findOrFail($taskId);
        $task->competencies()->detach($competencyId);
        return Redirect::route('tasks.edit', $taskId);
    }
	
	public function initializeTask($taskId) {
		$task = Task::findOrFail($taskId);
		$task->start_date = Carbon::now();
		$task->save();
		return Redirect::route('tasks.show',$taskId);
	}
	
	public function finishTask($taskId) {
		$task = Task::findOrFail($taskId);
		$task->end_date = Carbon::now();
		$task->save();
		return Redirect::route('tasks.show',$taskId);
	}
	
	public function showForm($taskId)
    {
        $task = Task::findOrFail($taskId);
		return view('tasks.show_form', ['task' => $task]);
    }
}
