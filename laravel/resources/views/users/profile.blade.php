@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="span3 well well-sm">
            <center>
                <!-- <a href="#aboutModal" data-toggle="modal" data-target="#myModal"><img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRbezqZpEuwGSvitKy3wrwnth5kysKdRqBW54cAszm_wiutku3R" name="aboutme" width="140" height="140" class="img-circle"></a>
                 -->
                <h3>{{ $user->name }}</h3>
                <div class="profile-usertitle-job text-capitalize">
                    {{ $user->level }}
                </div>
                <i class="icon-envelope"></i> {{$user->email}} <br>
            </center>
        </div>

        @if (Auth::user()->id == $user->id)
            @include('users.show_competences_for_endorsement', ['competences' => $user->competencies, 'profile_user' => $user, 'showEndorsementSection' => False])
        @else
            @include('users.show_competences_for_endorsement', ['competences' => $user->competencies, 'profile_user' => $user, 'showEndorsementSection' => True])
        @endif

        <div class="panel panel-default">
            <div class="panel-heading" >
                Tarefas criadas por este usuário
                @include('tasks.show_paginated_tasks', ['tasks' => $user->createdTasks()->paginate(10, ['*'],'tasks'), 'noTasksMessage' => 'Não há tarefas para exibição.'])
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" >
                Equipes das quais o usuário faz parte
                @include('teams.show_paginated_teams', ['teams' => $user->teams()->paginate(10, ['*'],'teams')])
            </div>
        </div>



    </div>
@endsection