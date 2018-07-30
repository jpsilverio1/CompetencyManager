@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            @include('tasks.show_tasks')
			@include('tasks.show_joined_tasks')
        </div>
        <div class="row">
            @include('users.show_competences')
            @include('users.add_competences_with_button')
        </div>
    </div>
@endsection