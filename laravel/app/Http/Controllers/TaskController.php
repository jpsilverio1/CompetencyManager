<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateTaskFormRequest;
use App\Http\Requests\EditTaskFormRequest;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Http\Request;
use App\Task;

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
        return view('tasks.create2');

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

        $names = $request->get('competence_names');
        $competenceIds = $request->get('competence_ids');
        $competenceLevels = $request->get('competence_levels');
        for ($i=0; $i<sizeOf($names); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceName = $names[$i];
            $competenceLevel = $competenceLevels[$i];
            $task->competencies()->attach([$competenceId => ['competency_level'=>$competenceLevel]]);
        }

        return $this->show($task->id, 'A tarefa foi cadastrada com sucesso!');

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
        $names = $request->get('competence_names');
        $competenceIds = $request->get('competence_ids');
        $competenceLevels = $request->get('competence_levels');
        for ($i=0; $i<sizeOf($names); $i++) {
            $competenceId = $competenceIds[$i];
            $competenceName = $names[$i];
            $competenceLevel = $competenceLevels[$i];
            echo "$competenceName - $competenceLevel<br>";
            $task->competencies()->attach([$competenceId => ['competency_level'=>$competenceLevel]]);
        }

        return $this->show($task->id, 'A tarefa foi atualizada com sucesso!');
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

		$allTasks = Task::paginate(10);
        return view('tasks.index', ['tasks' => $allTasks, 'message' => 'A tarefa foi excluída com sucesso!']);
	}

	public function deleteCompetenceFromTask($taskId, $competenceId) {
        $task = Task::findOrFail($taskId);
        $task->competencies()->detach($competenceId);
        return $this->edit($taskId);
    }
}
