<?php

namespace App\Http\Controllers;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competences = \Auth::user()->competencies;
        return view('home', [
             'competences' => $competences
        ]);
    }

    private function array_peek($stack) {
        if(empty($stack)) {
            return -1;
        }
        return $stack[0];
    }

    public function testa()
    {
        $last_indentation = 0;
        $stack = [];
        $database_id_stack = [];
        foreach(file(base_path('resources/assets/seeds/teste_seeding.txt')) as $competenceName) {
            $i = substr_count($competenceName,'	');
            echo "que coisa doida $competenceName $i <br>";
            if ($i<=$last_indentation) {
                $most = ($last_indentation - $i)+1;
                $stackSize = count($stack);
                echo " status = $most / $stackSize <br>";
                for ($j = 0; $j < $most; $j++) {
                    $elem = array_shift($stack);
                    $elem_id = array_shift($database_id_stack);
                    echo "removendo $elem <br>";
                }
            }
            $parent = $this->array_peek($stack);
            $parent_id = $this->array_peek($database_id_stack);
            //save to database
            $competence = new \App\Competency;
            $competence->name = $competenceName;
            $competence->description = "olar mundo";
            if ($parent_id > 0) {
                $competence->parent_id = $parent_id;
            }
            $competence->save();
            $database_id = $competence->id;
            array_unshift($database_id_stack,$database_id);
            array_unshift($stack, $competenceName);
            echo " ------------------------------------ <br>";
            echo "nozao: $competenceName" ;
            echo " || i= $i || last_id = $last_indentation || parent: $parent || parent database id= $parent_id || database id= $database_id || current stack: <br>";
            print_r($stack);

            echo "<br>";

            $last_indentation = $i;
        }
    }


}
