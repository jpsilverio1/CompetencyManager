@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="row">
            @if (!Auth::guest())
                @include('tasks.search_task')
            @endif
        </div>
        <form class="form-horizontal" role="form" method="POST" action="{{ route('tasks.index') }}">
            {{ csrf_field() }}
            <input type="hidden" name="sort_type" value="{{$sortType}}">
                <div class="form-group">
                    <div class="col-xs-5 col-xs-offset-1">
                        @if($sortType == "name")
                            <button type="submit" class="btn btn-primary">Ordenar por nome</button>
                        @else
                            <button type="submit" class="btn btn-primary">Ordenar por data</button>
                        @endif
                    </div>
                </div>
        </form>

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