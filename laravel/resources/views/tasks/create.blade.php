@extends('layouts.app')

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')

<h1>Cadastrar Tarefa</h1>



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
				
                //.find('[name="name"]').attr('name', 'competency[' + competencyIndex + '].name').end()
                //.find('[name="description"]').attr('name', 'competency[' + competencyIndex + '].description').end();
                .find('[name="title"]').attr('name', 'competency[' + competencyIndex + '].title').end()
                .find('[name="description"]').attr('name', 'competency[' + competencyIndex + '].description').end();
        
    });
	
	$('.btn-primary').click(function(){
            $('#taskTemplate').remove()
    });

    
    $('body').on('click','.removeButton',function(){
      var $row  = $(this).parents('.form-group'),
                index = $row.attr('data-book-index');



            // Remove element containing the fields
            $row.remove();   
    }); 

        
});

</script>
<div id="box">
{!! Form::open(
  array(
    'route' => 'tasks.store', 
    'class' => 'form')
  ) !!}

@if (count($errors) > 0)
<div class="alert alert-danger">
    Houve algum problema ao adicionar a Tarefa.<br />
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
	<div class="form-group">
        <label class="col-xs-1 control-label">Tarefa</label>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="title[]" value = "{{ $task->title or '' }}" placeholder="Título da Tarefa" />
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="description[]" value = "{{ $task->description or '' }}" placeholder="Descrição da Tarefa" />
        </div>
		
		@if (isset($task_competences))
			@if (count($task_competences) > 0)
				@foreach ($task_competences as $task_competence_row)
					<div class="col-xs-4">
						<input type="text" class="form-control" name="task_competences[]" value = "{{ $task_competence_row->name or '' }}" placeholder="ID da Competência" />
					</div>
					<div class="col-xs-4">
						<input type="text" class="form-control" name="task_competences[]" value = "{{ $task_competence_row->competency_level or '' }}" placeholder="Nível da Competência" />
					</div>
				@endforeach
			@endif
		@endif
			
        <div class="col-xs-1">
            <button type="button" class="btn btn-default addButton">+</button>
        </div>
    </div>
    
        <!-- The template for adding new field -->
    <div class="form-group hide" id="taskTemplate">
        <div class="col-xs-4 col-xs-offset-1">
            <input type="text" class="form-control" name="title[]" placeholder="Título da Tarefa" />
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="description[]" placeholder="Descrição da Tarefa" />
        </div>
      
        <div class="col-xs-1">
            <button type="button" class="btn btn-default removeButton">-</button>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-5 col-xs-offset-1">
            <button type="submit" class="btn btn-primary">Cadastrar Tarefa</button>
        </div>
    </div>

{!! Form::close() !!}
</div>

@endsection
