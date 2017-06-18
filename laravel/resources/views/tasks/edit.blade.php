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
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    Houve algum problema ao editar a tarefa.<br />
                                </div>
                            @endif
							<table class="table table-striped task-table" id="editTaskTable">
                                <tbody>
                                <tr>
									<input type="hidden" name="_method" value="put" />
									<input type="hidden" name="id" value="{{ $task->id }}" />
                                    <td class="form-group  col-md-5{{ $errors->has('title.0') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Tarefa</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="title[]" placeholder="Título da tarefa"  value="{{ old('title.0', $task->title) }}">
                                            @if ($errors->has('title.0'))
                                                <span class="help-block">
													<strong>{{ $errors->first('title.0') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description.0') ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description[]" placeholder="Descrição da tarefa" value="{{ old('description.0', $task->description) }}">
                                            @if ($errors->has('description.0'))
                                                <span class="help-block">
													<strong>{{ $errors->first('description.0') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="form-group">
                                <div class="col-xs-5 col-xs-offset-1">
                                    <button type="submit" class="btn btn-primary">Salvar Tarefa</button>
                                </div>
                            </div>
                        </form>
						<form class="col-xs-offset-1" id="deleteTasksForm" role="form" method="POST" action="{{ route('tasks.destroy', ['id' => $task->id] ) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<input type="hidden" name="id" value="{{ $task->id }}" />       
							<button type="" class="btn btn-danger">Excluir Tarefa</button>
						</form>
                    </div>
                </div>
        </div>
    </div>
@endsection