@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$task->title}}
                </h2>
            </div>
            <div class="panel-body">
                <h4>
                    Descrição
                </h4>
                <p>{{$task->description}}</p>
                <h4>
                    Autor
                </h4>
                <!--$task->author-->
                <p> TODO!!</p>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Usuários aptos a realizar a tarefa
                        @include('users.show_paginated_users', ['users' => $task->suitableAssignees()->paginate(10, ['*'],'users')])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências requeridas pela tarefa
                        @include('competences.show_paginated_competences', ['competences' => $task->competencies()->paginate(1, ['*'],'competences'),  'showCompetenceLevel' => True])
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Equipes aptas a realizar a tarefa
                        @include('teams.show_paginated_teams', ['teams' => $task->suitableTeams()->paginate(10, ['*'],'teams')])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
