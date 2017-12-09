@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-fullScreen">
                <div class="panel-heading">Editar Treinamento</div>
                <div class="panel-body">
                    <form class="form-horizontal" id="editLearningAidForm" role="form" method="POST" action="{{ route('learningaids.update', ['id' => $learningAid->id]) }}">
                        {{ csrf_field() }}
                        <table class="table table-striped learningaid-table" id="editLearningAidTable">
                            <tbody>
                            <tr>
                                <input type="hidden" name="_method" value="put" />
                                <input type="hidden" name="id" value="{{ $learningAid->id }}" />
                                <td class="form-group  col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-1 control-label">Treinamento</label>
                                    <div class=" col-md-offset-4">
                                        <input type="text" class="form-control" name="name" placeholder="Título da tarefa"  value="{{ old('name', $learningAid->name) }}">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <div class="">
                                        <input type="text" class="form-control" name="description" placeholder="Descrição" value="{{ old('description', $learningAid->description) }}">
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
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        @include('learningaids.show_paginated_competences_for_removal')
                    </div>
                    <form class="col-xs-offset-1" id="deleteLearningAidForm" role="form" method="POST" action="{{ route('learningaids.destroy', $learningAid->id ) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE" />
                        <button  class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
