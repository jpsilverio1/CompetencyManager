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
			
				@if (!empty($message) > 0)
                    <div class="alert alert-success">
                        {{$message}}<br />
						</div>
                @endif
			
               <h4>
                   Descrição
               </h4>
                <p>{{$competence->description}}</p>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                            Usuários que possuem a competência
                        @include('users.show_paginated_users', ['users' => $competence->skilledUsers()->paginate(10, ['*'],'users')])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Tarefas que necessitam desta competência
                        @include('tasks.show_paginated_tasks', ['tasks' => $competence->tasksThatRequireIt()->paginate(10, ['*'],'tasks'), 'noTasksMessage' => 'Não há tarefas para exibição.'])
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Equipes que possuem esta competência
                        @include('teams.show_paginated_teams', ['teams' => $competence->teamsThatHaveIt()->paginate(10, ['*'],'teams')])
                    </div>
                </div>
                    <div>
                        <div class="col-md-2">
                            <a href='{{ $competence->id."/edit"}}'/><button type="submit" class="btn btn-primary">Editar Competência</button>
                        </div>
                        <div>
                            <form  id="deleteCompetencesForm" role="form" method="POST" action="{{ route('competences.destroy', ['id' => $competence->id] ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE" />
                                <input type="hidden" name="id" value="{{ $competence->id }}" />
                                <td><button type="" class="btn btn-danger">Excluir Competência</button></td>
                            </form>
                        </div>


                    </div>


            </div>
        </div>
    </div>
@endsection
