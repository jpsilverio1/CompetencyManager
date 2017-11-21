@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$task->title}}
                </h2>
            </div>
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
                <h4>
                    Descrição
                </h4>
                <p>{{$task->description}}</p>
                <h4>
                    Autor
                </h4>
                <p> {{$task->author->name}}</p>
                 <h4>
                     Equipe
                 </h4>
                  @php ($teamMembers = $task->teamMembers)
                  @if (count($teamMembers) > 0)
                        @foreach($teamMembers as $teamMember)
                            <a href="{{ route('users.show', $teamMember->id) }}">{{ $teamMember->name }}</a>,
                        @endforeach
                  @else
                      Nenhuma equipe foi designada para este time até o momento.

                  @endif

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Usuários aptos a realizar a tarefa
                    </div>
                    <div class="panel-body">

                            <?php $suitableAssigneesForTask = $task->suitableAssigneesSets(); ?>
                                @if (count($suitableAssigneesForTask) > 0)
                                    <ul>
                                    @foreach($suitableAssigneesForTask as $users)
                                        <li> Grupo
                                            <ul>
                                                @foreach($users as $user)
                                                    <li><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></li>
                                                @endforeach

                                            </ul>
                                        </li>
                                    @endforeach
                                    </ul>
                                    @else
                                        Não há usuários aptos a realizar esta tarefa
                                    @endif
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências requeridas pela tarefa
                        @include('competences.show_paginated_competences', ['competences' => $task->competencies()->paginate(5, ['*'],'competences'),
                        'showCompetenceLevel' => True,
                        'showDeleteButton' => False,
                        'useCompetency' => True,
                        'noCompetencesMessage' => 'Não há competências para exibição.'])
                    </div>
                </div>

                    <div>
                        <div class="col-md-2">
                            <td><a href='{{ route('tasks.edit', $task->id) }}'/><button type="submit" class="btn btn-primary">Editar Tarefa</button></td>
                        </div>
                        <div>
                            <form class="col-xs-offset-1" id="deleteTaskForm" role="form" method="POST" action="{{ route('tasks.destroy', $task->id ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE" />
                                <td><button class="btn btn-danger">Excluir Tarefa</button></td>
                            </form>
                        </div>
                    </div>
				
            </div>
        </div>
    </div>
@endsection
