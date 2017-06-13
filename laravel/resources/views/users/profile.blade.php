@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            que profile legal!!
            {{ $user->name }}
            @include('users.show_competences', ['competences' => $user->competencies])
        </div>
    </div>
@endsection