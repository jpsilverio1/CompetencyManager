@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="col-md-6">
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