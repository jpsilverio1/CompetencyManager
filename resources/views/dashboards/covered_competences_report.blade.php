@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    Lista de Competências Abrangidas (por Pessoas ou Treinamentos)
                </h2>
            </div>
            <div class="panel-body">
				<center>Clique no nome da competência para ver quais pessoas ou treinamentos a possui</center>
				<br/>
				<?= Lava::render('TableChart', 'covered_competences_report', 'covered_competences_report_div') ?>
				<center><div id="covered_competences_report_div"></div></center>
			<br/>	

        </div>
    </div>
@endsection
