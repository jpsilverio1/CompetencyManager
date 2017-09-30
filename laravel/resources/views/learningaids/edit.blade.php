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
                    <form class="form-horizontal" id="editLearningAidsForm" role="form" method="POST"
                          action="{{ route('learningaids.update', ['id' => $learningaid->id] ) }}">
                        {{ csrf_field() }}

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                Houve algum problema ao editar o treinamento.<br/>
                            </div>
                        @endif

                        <table class="table table-striped task-table" id="editLearningAidsTable">
                            <tbody>
                            <tr>
                                <input type="hidden" name="_method" value="put"/>
                                <input type="hidden" name="id" value="{{ $learningaid->id }}"/>
                                <td class="form-group  col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-1 control-label">Treinamento</label>
                                    <div class=" col-md-offset-4">
                                        <input type="text" class="form-control" name="name"
                                               placeholder="Nome do Treinamento"
                                               value="{{ old('name', $learningaid->name)}}">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
                                        @endif
                                    </div>

                                </td>
                                <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <div class="">
                                        <input type="text" class="form-control" name="description"
                                               placeholder="Descrição do treinamento"
                                               value="{{ old('description', $learningaid->description) }}">
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

                     {{--   <div class="form-group">
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </div>
                    </form>
                    <form class="col-xs-offset-1" id="deleteLearningAidsForm" role="form" method="POST"
                          action="{{ route('competences.destroy', $learningaid->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-danger">Excluir</button>
                    </form>

                        <div class="form-group">--}}
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                        {{--</div>--}}
                    </form>
                        <div>
                            <form  id="deleteLearningAidsForm" role="form" method="POST" action="{{ route('learningaids.destroy', $learningaid->id ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE" />
                                <td><button type="" class="btn btn-danger">Excluir</button></td>
                            </form>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection