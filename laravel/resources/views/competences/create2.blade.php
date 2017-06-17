@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-fullScreen">
                    <div class="panel-heading">Criar novas competências</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="addCompetencesForm"role="form" method="POST" action="{{ route('competences.store') }}">
                            {{ csrf_field() }}
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
                            <table class="table table-striped task-table" id="addCompetencesTable">
                                <tbody>
                                    <tr>
                                        <td class="form-group  col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="col-md-1 control-label">Competência</label>
                                            <div class=" col-md-offset-4">
                                                <input type="text" class="form-control" name="name[]" placeholder="Nome da competência" value="{{ old('name') }}">

                                            </div>

                                        </td>
                                        <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                            <div class="">
                                                <input type="text" class="form-control" name="description[]" placeholder="Descrição da competência" value="{{ old('description') }}">

                                            </div>
                                        </td>
                                        <td class="form-group">
                                            <button type="button" class="btn btn-default addButton">+</button>
                                        </td>
                                    </tr>
                                    <tr class="hide start-form-row" id="competenceTemplate">
                                        <td class="form-group  col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="col-md-1 control-label">Competência</label>
                                            <div class=" col-md-offset-4">
                                                <input type="text" class="form-control" name="name[]" placeholder="Nome da competência" value="{{ old('name') }}" >

                                            </div>

                                        </td>
                                        <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                            <div class="">
                                                <input type="text" class="form-control" name="description[]" placeholder="Descrição da competência" value="{{ old('description') }}">

                                            </div>
                                        </td>
                                        <td class="form-group">
                                            <button type="button" class="btn btn-default removeButton">-</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="form-group">
                                <div class="col-xs-5 col-xs-offset-1">
                                    <button type="submit" class="btn btn-primary">Cadastrar Competência</button>
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
        var competencyIndex = 0;
        $('.addButton').click(function(){
            competencyIndex++;
            var $template = $('#competenceTemplate'),
                $clone    = $template
                    .clone()
                    .removeClass('hide')
                    .removeAttr('id')
                    .insertBefore($template);
            $clone.find('[name="name"]').attr('name', 'competency[' + competencyIndex + '].name').end()
                .find('[name="description"]').attr('name', 'competency[' + competencyIndex + '].description').end();

        });

        $('.btn-primary').click(function(){
                $('#competenceTemplate').remove();


        });


        $('body').on('click','.removeButton',function(){
            var $row  = $(this).parents('.start-form-row');
            // Remove element containing the fields
            $row.remove();
        });


    });

</script>