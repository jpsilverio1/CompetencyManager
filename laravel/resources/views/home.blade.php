@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            @include('tasks.show_tasks')
        </div>
        <div class="row">
            @include('users.show_competences')
            @include('users.add_competences_with_button')
            @if (Auth::user()->isManager())
                @include('users.manager_actions')
            @endif
        </div>
    </div>
@endsection