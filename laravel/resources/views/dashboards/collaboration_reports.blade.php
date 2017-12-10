@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Dashboard
                </h2>
            </div>
            <div class="panel-body">
			
			
			
			
			<?= Lava::render('AreaChart', 'Population', 'pop_div') ?>
				<div id="pop_div"></div>
				

				<div>
                <h4>
                    Descrição
                </h4>
                <p>oi</p>
                <h4>
                    Autor
                </h4>
                <p> oi</p>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Usuários aptos a realizar a tarefa
                    </div>
                    <div class="panel-body">
							oioi
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        oiee
                    </div>
                </div>
				
            </div>
        </div>
    </div>
@endsection
