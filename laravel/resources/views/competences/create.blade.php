@extends('layouts.app')

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')

<h1>Cadastrar Competência</h1>



<script>

$(document).ready(function(){
    
    $('#add').click(function(){
        
        var inp = $('#box');
        
        var i = $('input').size() + 1;
        
        $('<div id="box' + i +'"><input type="text" id="name" class="name" name="name' + i +'" placeholder="Input '+i+'"/><img src="remove.png" width="32" height="32" border="0" align="top" class="add" id="remove" /> </div>').appendTo(inp);
        
        i++;
        
    });
    
    
    
    $('body').on('click','#remove',function(){
        
        $(this).parent('div').remove();

        
    });

        
});

</script>

<a href="#" id="add">Add More Input Field</a>

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
    {!! Form::label('Competência') !!}
    {!! Form::text('name', null, 
      array(
        'class'=>'form-control', 
        'placeholder'=>'Nome da Competência'
      )) !!}
	  {!! Form::label('Descrição') !!}
    {!! Form::text('description', null, 
      array(
        'class'=>'form-control', 
        'placeholder'=>'Descrição da Competência'
      )) !!}
</div>

<div class="form-group">
    {!! Form::submit('Cadastrar Competência', 
      array('class'=>'btn btn-primary'
    )) !!}
</div>
{!! Form::close() !!}
</div>

@endsection
