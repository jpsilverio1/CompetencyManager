<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateTaskFormRequest;
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
        if (\Auth::user()->isManager()) {
            return view('tasks.create', ['task' => $task]);
        } else {
            return redirect('/home');
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTaskFormRequest $request)
    {
		
		$titles = $request->get('title');
		$description = $request->get('description');
		$author_id = \Auth::user()->id;

		for ($i=0; $i<sizeOf($titles); $i++) {
			$task = new \App\Task; 
			$task->title = $titles[$i];
			$task->description = $description[$i];
			$task->author_id = $author_id;
			$task->save();
		} 
		
		$allTasks = Task::paginate(10);
        return view('tasks.index', ['tasks' => $allTasks, 'message' => 'As tarefas foram cadastradas com sucesso!']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {	
		$task = Task::findOrFail($id);
		return view('tasks.show', ['task' => $task]);
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
    public function update(CreateTaskFormRequest $request, $id)
    {
        $titles = $request->get('title');
		$description = $request->get('description');

		for ($i=0; $i<sizeOf($titles); $i++) {
			Task::findOrFail($id)->update(['title' => $titles[$i], 'description' => $description[$i]]);
		} 
		
		$task = Task::findOrFail($id);
        return view('tasks.show', ['id' => $id, 'task' => $task, 'message' => 'A tarefa foi atualizada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
