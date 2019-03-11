@extends('layouts.app')
@section('content')
    <div class="container">
        <?php $taskStatus = $task->taskStatus(); ?>
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
                @if ($taskStatus == "created")
                    <h4>Status
                        <div class="glyphicon glyphicon-info-sign task-team-creation-user-popover" data-container="body" data-toggle = "popover" data-placement = "right"  data-html="true"
                             data-content="Uma tarefa é executável se para cada competência requerida pela tarefa, existe pelo menos um usuário que possua tal competência ou um treinamento que ensine a mesma."></div>
                    </h4>
                    @if($task->isFeasible())
                        <p>Executável</p>
                    @else
                        <p>Não executável</p>
                    @endif
                @endif
                 <h4>
                     Equipe
                 </h4>

                  @php ($teamMembers = $task->teamMembers)
                  @if (count($teamMembers) > 0)
                        @foreach($teamMembers as $index => $teamMember)
                            <a href="{{ route('users.show', $teamMember->id) }}">{{ $teamMember->name }}</a>
                            @if($index < (count($teamMembers) - 1))
                                ,
                            @endif
                        @endforeach
                  @else
                      Nenhuma equipe foi designada para esta tarefa até o momento.

                  @endif
                    @if($task->canHaveTeamAssigned())
                        @include('tasks.task_team_creation_assistant')
                    @else
                        <div class="alert alert-warning">
                            Não é possível alterar ou criar equipes para tarefas que já foram finalizadas.
                        </div>
                    @endif


   <div class="panel panel-default">
      <div class="panel-heading" >
          Competências requeridas pela tarefa
          @include('competences.show_paginated_competences', ['competences' => $task->competencies()->paginate(5, ['*'],'competences'),
          'showCompetenceLevel' => True,
          'showDeleteButton' => False,
          'noCompetencesMessage' => 'Não há competências para exibição.'])
      </div>
   </div>
                       <div>
                           <div class="col-md-2">
                               <td><a href='{{ route('tasks.edit', $task->id) }}'/><button type="submit" class="btn btn-primary">Editar Tarefa</button></td>
                           </div>
                           <div>
                               <form class="col-md-2" id="deleteTaskForm" role="form" method="POST" action="{{ route('tasks.destroy', $task->id ) }}">
                                   {{ csrf_field() }}
                                   <input type="hidden" name="_method" value="DELETE" />
                                   <td><button class="btn btn-danger">Excluir Tarefa</button></td>
                               </form>
                           </div>
                           <div class="col-md-2">
                               <?php $userCanInitializeTask = \Auth::user()->canInitializeOrFinishTask($task->id); ?>
                               @if ($userCanInitializeTask)
                                   @if ($taskStatus == "created")
                                       <td><a href=""/><button type="submit" class="btn btn-primary" disabled alt="A tarefa só pode ser inicializada após a designação de uma equipe à ela">Tarefa Não-Inicializada</button></td>
                                   @elseif ($taskStatus == "teamAssigned")
                                       <td><a href="{{ route('task-initialize', $task->id) }}"/><button type="submit" class="btn btn-primary">Inicializar Tarefa</button></td>
                                   @elseif ($taskStatus == "initialized")
                                       <td><a href="{{ route('task-finish', $task->id) }}"/><button type="submit" class="btn btn-primary">Finalizar Tarefa</button></td>
                                   @elseif ($taskStatus == "finished")
                                       <?php $userAnsweredQuestions = \Auth::user()->answeredQuestions($task->id); ?>
                                       @if ($userAnsweredQuestions)
                                           <td><a href=''/><button type="submit" class="btn btn-primary" disabled>Tarefa Finalizada - Questionário Respondido!</button></td>
                                       @else
                                           <td><a href="{{ route('show-task-form', $task->id) }}"/><button type="submit" class="btn btn-primary">Tarefa Finalizada - Responder Questionário</button></td>
                                       @endif
                                   @endif
                               @else
                                   @if ($taskStatus == "created")
                                       <td><a href=""/><button type="submit" class="btn btn-primary" disabled alt="Você não tem autorização para inicializar esta tarefa pois não faz parte desta equipe">Tarefa Não-Inicializada</button></td>
                                   @elseif ($taskStatus == "initialized")
                                       <td><a href=""/><button type="submit" class="btn btn-primary" disabled alt="Você não tem autorização para finalizar esta tarefa pois não faz parte desta equipe">Tarefa em Andamento</button></td>
                                   @elseif ($taskStatus == "finished")
                                       <?php $userAnsweredQuestions = \Auth::user()->answeredQuestions($task->id); ?>
                                       @if ($userAnsweredQuestions)
                                           <td><a href=''/><button type="submit" class="btn btn-primary" disabled>Tarefa Finalizada - Questionário Respondido!</button></td>
                                       @else
                                           <td><a href="{{ route('show-task-form', $task->id) }}"/><button type="submit" class="btn btn-primary">Tarefa Finalizada - Responder Questionário</button></td>
                                       @endif
                                   @endif
                               @endif
                           </div>
                       </div>

               </div>
           </div>
       </div>
   @endsection

