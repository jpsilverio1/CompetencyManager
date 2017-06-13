@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            @include('teams.show_teams')
            @include('tasks.show_tasks')
        </div>
        <div class="row">
            @include('users.show_competences')
            @include('users.add_competences')
        </div>
    </div>
@endsection