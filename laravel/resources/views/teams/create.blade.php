@extends('layouts.app')

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')

<h1>Cadastrar Equipe</h1>



<script>

$(document).ready(function(){
    taskIndex = 0;
    $('.addButton').click(function(){
      teamIndex++;
            var $template = $('#teamTemplate'),
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
                .find('[name="name"]').attr('name', 'competency[' + teamIndex + '].name').end()
                .find('[name="description"]').attr('name', 'competency[' + teamIndex + '].description').end();
        
    });
	
	$('.btn-primary').click(function(){
            $('#teamTemplate').remove()
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
    'route' => 'teams.store', 
    'class' => 'form')
  ) !!}

@if (count($errors) > 0)
<div class="alert alert-danger">
    Houve algum problema ao adicionar a Equipe.<br />
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
	<div class="form-group">
        <label class="col-xs-1 control-label">Equipe</label>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="name[]" value = "{{ $team->name or '' }}" placeholder="Nome da Equipe" />
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="description[]" value = "{{ $team->description or '' }}" placeholder="Descrição da Equipe" />
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
    <div class="form-group hide" id="teamTemplate">
        <div class="col-xs-4 col-xs-offset-1">
            <input type="text" class="form-control" name="name[]" placeholder="Nome da Equipe" />
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="description[]" placeholder="Descrição da Equipe" />
        </div>
      
        <div class="col-xs-1">
            <button type="button" class="btn btn-default removeButton">-</button>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-5 col-xs-offset-1">
            <button type="submit" class="btn btn-primary">Cadastrar Equipe</button>
        </div>
    </div>

{!! Form::close() !!}
</div>

@endsection
