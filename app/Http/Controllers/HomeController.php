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
        /*$competences = \Auth::user()->competencies;
        return view('home', [
            'competences' => $competences
        ]); */
        $stack =[];
        /*array_unshift($stack,"a");
        array_unshift($stack,"b");
        array_unshift($stack,"c");
        //$stack[] = "a";
        //$stack[] = "b";
        //array_pop($stack);
        //echo $this->array_peek($stack);
        $elemento = array_shift($stack);
        echo " oi $elemento<br>";

        $elemento = array_shift($stack);
        echo " oi $elemento<br>";

        $elemento = array_shift($stack);
        echo " oi $elemento<br>";

        echo "   olar";*/
        $last_indentation = 0;
        $stack = [];
        foreach(file(base_path('resources/assets/seeds/teste_seeding.txt')) as $competenceName) {
            $i = substr_count($competenceName,'	');
            echo "que coisa doida $competenceName $i <br>";
            if ($i<=$last_indentation) {
                $most = ($last_indentation - $i)+1;
                $stackSize = count($stack);
                echo " status = $most / $stackSize <br>";
                for ($j = 0; $j < $most; $j++) {
                    $elem = array_shift($stack);
                    echo "removendo $elem <br>";
                }
            }
            $parent = $this->array_peek($stack);
            array_unshift($stack, $competenceName);
            echo " ------------------------------------ <br>";
            echo "nozao: $competenceName" ;
            echo " || i= $i || last_id = $last_indentation || parent: $parent current stack: <br>";
            print_r($stack);

            echo "<br>";

            $last_indentation = $i;
        }
    }


}
