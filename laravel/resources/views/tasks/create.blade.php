@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-fullScreen">
                    <div class="panel-heading">Criar nova tarefa</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="addTaskForm" role="form" method="POST" action="{{ route('tasks.store') }}">
                            {{ csrf_field() }}
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    Houve algum problema ao adicionar a tarefa.<br />
                                </div>
                            @endif
							<table class="table table-striped task-table" id="addTaskTable">
                                <tbody>
                                <tr>
                                    <td class="form-group  col-md-5{{ $errors->has('name.0') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Tarefa</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="title[]" placeholder="Título da tarefa"  value="{{ old('title.0') }}">
                                            @if ($errors->has('name.0'))
                                                <span class="help-block">
													<strong>{{ $errors->first('name.0') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description.0') ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description[]" placeholder="Descrição da tarefa" value="{{ old('description.0') }}">
                                            @if ($errors->has('description.0'))
                                                <span class="help-block">
													<strong>{{ $errors->first('description.0') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="form-group">
                                        <button type="button" class="btn btn-default addButton">+</button>
                                    </td>
                                </tr>
                                <tr class="hide start-form-row" id="taskTemplate">
                                    <td class="form-group  col-md-5">
                                        <label for="name" class="col-md-1 control-label">Tarefa</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="title[]" placeholder="Título da tarefa">
                                        </div>
                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2">
                                        <div class="">
                                            <input type="text" class="form-control" name="description[]" placeholder="Descrição da tarefa">
                                        </div>
                                    </td>
                                    <td class="form-group">
                                        <button type="button" class="btn btn-default removeButton">-</button>
                                    </td>
                                </tr>
                                    @for ($i=1; $i<sizeOf(old('title')); $i++)
                                        <tr class="start-form-row">
                                            <td class="form-group  col-md-5{{ $errors->has("name.$i") ? ' has-error' : '' }}">
                                                <label for="name" class="col-md-1 control-label">Tarefa</label>
                                                <div class=" col-md-offset-4">
                                                    <input type="text" class="form-control" name="title[]" placeholder="Título da tarefa"  value="{{ old('title.$i') }}">
                                                    @if ($errors->has("name.$i"))
                                                        <span class="help-block">
															<strong>{{ $errors->first("name.$i") }}</strong>
														</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="form-group col-md-5 col-md-offset-2{{ $errors->has("description.$i") ? ' has-error' : '' }}">
                                                <div class="">
                                                    <input type="text" class="form-control" name="description[]" placeholder="Descrição da tarefa" value="{{ old('description.$i') }}">
                                                    @if ($errors->has("description.$i"))
                                                        <span class="help-block">
															<strong>{{ $errors->first("description.$i") }}</strong>
														</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="form-group">
                                                <button type="button" class="btn btn-default removeButton">-</button>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>

                            <div class="form-group">
                                <div class="col-xs-5 col-xs-offset-1">
                                    <button type="submit" class="btn btn-primary">Cadastrar Tarefa</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
@endsection


<script>

$(document).ready(function(){
    taskIndex = 0;
    $('.addButton').click(function(){
      taskIndex++;
            var $template = $('#taskTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', taskIndex)
                                .insertBefore($template);
            // Update the name attributes
            $clone
                .find('[name="title"]').attr('name', 'task[' + competencyIndex + '].title').end()
                .find('[name="description"]').attr('name', 'task[' + competencyIndex + '].description').end();  
    });
	
	$('.btn-primary').click(function(){
            $('#taskTemplate').remove()
    });

    
    $('body').on('click','.removeButton',function(){
      var $row  = $(this).parents('.start-form-row'),
                index = $row.attr('data-book-index');

            // Remove element containing the fields
            $row.remove();   
    });      
});

</script>