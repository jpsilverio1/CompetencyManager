<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateLearningAidFormRequest;
use App\Http\Requests\EditLearningAidFormRequest;
use Illuminate\Support\Facades\Redirect;


use App\LearningAid;

class LearningAidController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $allLearningaids = LearningAid::paginate(10);
        return view('learningaids.index', ['learningaids' => $allLearningaids, 'message' => '']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (\Auth::user()->isManager()) {
            return view('learningaids.create');
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
    public function store(CreateLearningAidFormRequest $request)
    {
        $names = $request->get('name');
        $description = $request->get('description');

        for ($i=0; $i<sizeOf($names); $i++) {
            $learningaid = new \App\LearningAid();
            $learningaid->name = $names[$i];
            $learningaid->description = $description[$i];
            $learningaid->save();
        }
        $allLearningAids = LearningAid::paginate(10);
        return view('learningaids.index', ['learningaids' => $allLearningAids, 'message' => 'Os treinamentos foram cadastrados com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.show', ['learningaid' => $learningaid]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.edit', ['learningaid' => $learningaid]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditLearningAidFormRequest $request, $id)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        LearningAid::findOrFail($id)->update(['name' => $name, 'description' => $description]);
        $learningaid = LearningAid::findOrFail($id);
        return view('learningaids.show', ['id' => $id, 'learningaid' => $learningaid, 'message' => 'O treinamento foi atualizado com sucesso!']);
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
        $learningaid = LearningAid::findOrFail($id);
        /* Deixando aqui para tentar fazer funcionar futuramente
        foreach ($competence->skilledUsers() as $user) {
            $user->endorsements()->detach();
        } */
        //DB::table("user_endorsements")->where('competence_id', '=',$competence->id)->delete();
        $learningaid->unskilledUsers()->detach();
        //$learningaid->tasksThatRequireIt()->detach();
        //$learningaid->teamsThatHaveIt()->detach();
        $learningaid->delete();

        return Redirect::route('learningaids.index')->withMessage('O treinamento foi excluído com sucesso!');

    }
}
