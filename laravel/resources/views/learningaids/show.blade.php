@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$learningaid->name}}
                </h2>
            </div>
            <div class="panel-body">
				@if (!empty($message) > 0)
                    <div class="alert alert-success">
                        {{$message}}<br />
						</div>
                @endif
               <h4>
                   Descrição
               </h4>
                <p>{{$learningaid->description}}</p>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                            Usuários indicados
                        @include('users.show_paginated_users', ['users' => $learningaid->unskilledUsers()->paginate(10, ['*'],'users')])
                    </div>
                </div>
                {{--<div class="panel panel-default">
                    <div class="panel-heading" >
                        Tarefas pendentes
                        {{--@include('tasks.show_paginated_tasks', ['tasks' => $learningaid->tasksThatRequireIt()->paginate(10, ['*'],'tasks'), 'noTasksMessage' => 'Não há tarefas para exibição.'])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Equipes pendentes
                        {{--@include('teams.show_paginated_teams', ['teams' => $learningaid->teamsThatHaveIt()->paginate(10, ['*'],'teams')])
                    </div>
                </div>--}}
                    <div>
                        <div class="col-md-1">
                            <a href='{{ route('learningaids.edit', $learningaid->id) }}'/>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                        <div>
                            <form id="deleteLearningAidsForm" role="form" method="POST"
                                  action="{{ route('learningaids.destroy', $learningaid->id ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE"/>
                                <td>
                                    <button type="" class="btn btn-danger">Excluir</button>
                                </td>
                            </form>
                        </div>


                    </div>
            </div>
        </div>
    </div>
@endsection
