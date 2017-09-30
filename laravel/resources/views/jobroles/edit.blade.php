@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-fullScreen">
                    <div class="panel-heading">Editar uma tarefa</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="editTaskForm" role="form" method="POST" action="{{ route('tasks.update', ['id' => $task->id]) }}">
                            {{ csrf_field() }}
							<table class="table table-striped task-table" id="editTaskTable">
                                <tbody>
                                <tr>
									<input type="hidden" name="_method" value="put" />
									<input type="hidden" name="id" value="{{ $task->id }}" />
                                    <td class="form-group  col-md-5{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Tarefa</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="title" placeholder="Título da tarefa"  value="{{ old('title', $task->title) }}">
                                            @if ($errors->has('title'))
                                                <span class="help-block">
													<strong>{{ $errors->first('title') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description" placeholder="Descrição da tarefa" value="{{ old('description', $task->description) }}">
                                            @if ($errors->has('description'))
                                                <span class="help-block">
													<strong>{{ $errors->first('description') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div>
                                @if ($errors->has('competence_ids'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('competence_ids') }}</strong>
                                    </span>
                                @endif
                                @include('competences.add_competences_without_button', ['showCompetenceLevel' => True])
                            </div>

                            <div class="form-group">
                                <div class="col-xs-5 col-xs-offset-1">
                                    <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            @include('tasks.show_paginated_competences_for_removal')
                        </div>
						<form class="col-xs-offset-1" id="deleteTasksForm" role="form" method="POST" action="{{ route('tasks.destroy', $task->id ) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<button  class="btn btn-danger">Excluir Tarefa</button>
						</form>
                    </div>
                </div>
        </div>
    </div>
@endsection