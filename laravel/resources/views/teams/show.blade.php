@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$team->name}}
                </h2>
            </div>
            <div class="panel-body">
                <h4>
                    Descrição
                </h4>
                <p>{{$team->description}}</p>
                <h4>
                    Autor
                </h4>
                <p> TODO? </p>

                
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências para esta Equipe
                        @include('competences.show_paginated_competences', ['competences' => $team->competencies() ->paginate(5, ['*'],'competences'),
                        'showCompetenceLevel' => False,
                        'showDeleteButton' => False,
                        'noCompetencesMessage' => 'Não há competências para exibição.'])
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Integrantes desta equipe
                        @include('users.show_paginated_users', ['users' => $team->teamMembers()->paginate(5, ['*'],'teams')])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
