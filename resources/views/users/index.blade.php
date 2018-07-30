@extends('layouts.app')
@section('content')


<div class="container">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Usuários</div>
            <div class="panel-body">
                @include('users.show_paginated_users')
            </div>
        </div>
    </div>

</div>

@endsection