@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-fullScreen">
                <div class="panel-heading"><h3>Adicionar Treinamento</h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" id="addLearningAidForm"role="form" method="POST" action="{{ route('learningaids.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">Nome</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-2 control-label">Descrição:</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="2" id="description" name="description">{{old('description')}}</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
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
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-fullScreen">
                <div class="panel-heading">Criar novos Treinamentos</div>
                <div class="panel-body">
                    <form class="form-horizontal" id="addLearningAidForm"role="form" method="POST" action="{{ route('learningaids.store') }}">
                        {{ csrf_field() }}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                Houve algum problema ao adicionar os treinamentos.<br />
                            </div>
                        @endif
                        <table class="table table-striped task-table" id="addLearningAidsTable">
                            <tbody>
                            <tr>
                                <td class="form-group  col-md-5{{ $errors->has('name.0') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-1 control-label">Treinamento</label>
                                    <div class=" col-md-offset-4">
                                        <input type="text" class="form-control" name="name[]" placeholder="Nome do treinamento"  value="{{ old("name.0") }}">
                                        @if ($errors->has('name.0'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('name.0') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                </td>
                                <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description.0') ? ' has-error' : '' }}">
                                    <div class="">
                                        <input type="text" class="form-control" name="description[]" placeholder="Descrição do treinamento" value="{{ old("description.0") }}">
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
                            <tr class="hide start-form-row" id="learningaidTemplate">
                                <td class="form-group  col-md-5">
                                    <label for="name" class="col-md-1 control-label">Treinamento</label>
                                    <div class=" col-md-offset-4">
                                        <input type="text" class="form-control" name="name[]" placeholder="Nome do treinamento"  >

                                    </div>

                                </td>
                                <td class="form-group col-md-5 col-md-offset-2">
                                    <div class="">
                                        <input type="text" class="form-control" name="description[]" placeholder="Descrição do treinamento">

                                    </div>
                                </td>
                                <td class="form-group">
                                    <button type="button" class="btn btn-default removeButton">-</button>
                                </td>
                            </tr>
                            @for ($i=1; $i<sizeOf(old('name')); $i++)
                                <tr class="start-form-row">
                                    <td class="form-group  col-md-5{{ $errors->has("name.$i") ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Treinamento</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="name[]" placeholder="Nome do treinamento"  value="{{ old("name.$i") }}">
                                            @if ($errors->has("name.$i"))
                                                <span class="help-block">
                                        <strong>{{ $errors->first("name.$i") }}</strong>
                                    </span>
                                            @endif
                                        </div>

                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has("description.$i") ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description[]" placeholder="Descrição do treinamento" value="{{ old("description.$i") }}">
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
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
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
        var learningaidIndex = 0;
        $('.addButton').click(function(){
            if (learningaidIndex <9) {
                learningaidIndex++;
                console.log(learningaidIndex);
                var $template = $('#learningaidTemplate'),
                    $clone    = $template
                        .clone()
                        .removeClass('hide')
                        .removeAttr('id')
                        .insertBefore($template);
                $clone.find('[name="name"]').attr('name', 'learningaid[' + learningaidIndex + '].name').end()
                    .find('[name="description"]').attr('name', 'learningaid[' + learningaidIndex + '].description').end();
            }
            else {
                alert("Não é possível criar mais de 10 treinamentos por vez");
            }


        });

        $('.btn-primary').click(function(){
            $('#learningaidTemplate').remove();

        });


        $('body').on('click','.removeButton',function(){
            var $row  = $(this).parents('.start-form-row');
            // Remove element containing the fields
            $row.remove();
            learningaidIndex--;
        });


    });

</script> --}}