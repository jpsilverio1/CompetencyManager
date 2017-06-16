@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$competence->name}}
                </h2>
            </div>
            <div class="panel-body">
               <h4>
                   Descrição
               </h4>
                <p>{{$competence->description}}</p>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                            Usuários que possuem a competência
                        @include('users.show_paginated_users', ['users' => $competence->skilledUsers()->paginate(15)])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
