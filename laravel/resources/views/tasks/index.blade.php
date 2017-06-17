@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Tarefas</div>

                <div class="panel-body">
                    @include('tasks.show_paginated_tasks')
                </div>
            </div>
        </div>
    </div>


@endsection