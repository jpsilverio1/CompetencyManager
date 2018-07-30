@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="row">
            @if (!Auth::guest())
                @include('tasks.search_task')
            @endif
                <div class="navbar-form col-md-3">
                    @if($sortType == "name")
                        <a href="{{ Request::fullUrlWithQuery(['sort' => 'name']) }}" class="btn btn-default" role="button">Ordenar por nome</a>
                    @else
                        <a href="{{ Request::fullUrlWithQuery(['sort' => 'date']) }}"class="btn btn-default" role="button">Ordenar por data</a>
                    @endif
                </div>
        </div>


        <div class="col-md-6 row">
            <div class="panel panel-default">
				
                <div class="panel-heading">Tarefas</div>

                <div class="panel-body">
					
					@if (!empty($message) > 0)
                        <div class="alert alert-success">
                            {{$message}}<br />
                        </div>
                    @endif
                        @if (Session::has('message'))
                            <div class="alert alert-success">
                                {{Session::get('message')}}<br />
                            </div>
                        @endif
					
                    @include('tasks.show_paginated_tasks', ['noTasksMessage' => 'Não há tarefas para exibição.'])
                </div>
            </div>
        </div>
    </div>


@endsection