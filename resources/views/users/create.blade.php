@extends('layouts.app')

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')

<h1>Cadastrar Competência</h1>



<script>

$(document).ready(function(){
    competencyIndex = 0;
    $('.addButton').click(function(){
      competencyIndex++;
            var $template = $('#competencyTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', competencyIndex)
                                .insertBefore($template);
            // Update the name attributes
            $clone
				
                //.find('[name="name"]').attr('name', 'competency[' + competencyIndex + '].name').end()
                //.find('[name="description"]').attr('name', 'competency[' + competencyIndex + '].description').end();
                .find('[name="name"]').attr('name', 'competency[' + competencyIndex + '].name').end()
                .find('[name="description"]').attr('name', 'competency[' + competencyIndex + '].description').end();
        
    });
	
	$('.btn-primary').click(function(){
            $('#competencyTemplate').remove()
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
    'route' => 'competences.store', 
    'class' => 'form')
  ) !!}

@if (count($errors) > 0)
<div class="alert alert-danger">
    Houve algum problema ao adicionar a Competência.<br />
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
 <div class="form-group">
        <label class="col-xs-1 control-label">Competência</label>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="name[]" placeholder="Nome da competência" />
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="description[]" placeholder="Descrição da competência" />
        </div>
        <div class="col-xs-1">
            <button type="button" class="btn btn-default addButton">+</button>
        </div>
    </div>
    
        <!-- The template for adding new field -->
    <div class="form-group hide" id="competencyTemplate">
        <div class="col-xs-4 col-xs-offset-1">
            <input type="text" class="form-control" name="name[]" placeholder="Nome da competência" />
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" name="description[]" placeholder="Descrição da competência" />
        </div>
      
        <div class="col-xs-1">
            <button type="button" class="btn btn-default removeButton">-</button>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-5 col-xs-offset-1">
            <button type="submit" class="btn btn-primary">Cadastrar Competência</button>
        </div>
    </div>

{!! Form::close() !!}
</div>

@endsection
