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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'title.*' => 'required|unique:tasks,title',
			'description.*' => 'required',
		]);
		
		if ($validator->fails()) {
            return redirect('tasks/create')
                        ->withErrors($validator)
                        ->withInput();
        }
		
		$titles = $request->get('title');
		$description = $request->get('description');

		for ($i=0; $i<sizeOf($titles); $i++) {
			$task = new \App\Task; 
			$task->title = $titles[$i];
			$task->description = $description[$i];
			$task->save();
			
		} 
		return \Redirect::route('tasks.show', 
			array($task->id))
			->with('message', 'A tarefa foi cadastrada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {		
		//$task = DB::table('tasks')->where('id', $id)->first();
		//$task_competences = DB::table('task_competencies')->where('task_id', $id)->join('competencies', 'competencies.id', '=', 'task_competencies.competency_id')->get();
        $task = Task::findOrFail($id);
        //$task->suitableAssigneesSets;
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
