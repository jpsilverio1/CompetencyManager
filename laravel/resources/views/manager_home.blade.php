@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            @include('teams.show_teams')
            @include('tasks.show_tasks')
        </div>


        <div class="row">
            <!-- Show competencies -->
            @include('users.show_competences')
            <!-- Add competencies -->
            @include('users.add_competences')
            <!--Manager actions -->
            @include('users.manager_actions')
        </div>

    </div>





@endsection
