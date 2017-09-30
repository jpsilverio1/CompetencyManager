@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$jobrole->title}}
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
                <p>{{$jobrole->description}}</p>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Usuários aptos a assumir este cargo
                    </div>
                    <div class="panel-body">

					{{-- --}}
                              olar
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências requeridas para este Cargo
                        @include('competences.show_paginated_competences', ['competences' => $jobrole->competencies()->paginate(5, ['*'],'competences'),
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
