@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-fullScreen">
                    <div class="panel-heading">Editar competência</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="editCompetencesForm" role="form" method="POST" action="{{ route('competences.update', ['id' => $competence->id] ) }}">
                            {{ csrf_field() }}
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    Houve algum problema ao editar a competência.<br />
                                </div>
                            @endif
							
                            <table class="table table-striped task-table" id="editCompetencesTable">
                                <tbody>
                                <tr>
									<input type="hidden" name="_method" value="put" />
									<input type="hidden" name="id" value="{{ $competence->id }}" />
                                    <td class="form-group  col-md-5{{ $errors->has('name.0') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Competência</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="name" placeholder="Nome da competência"  value="{{ old('name', $competence->name)}}">
                                            @if ($errors->has('name'))
                                                <span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
                                            @endif
                                        </div>

                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description" placeholder="Descrição da competência" value="{{ old('description', $competence->description) }}">
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
                            <div class="form-group">
                                <div class="col-xs-5 col-xs-offset-1">
                                    <button type="submit" class="btn btn-primary">Salvar Competência</button>
                                </div>
                            </div>
                        </form>
                        <div class="panel panel-default">
                            <div class="panel-heading">Editar Hierarquia da Competência</div>
                            <div class="panel-body">
                                <table class="table">
                                    <thead>
                                    <th>Competência pai</th>
                                    <th>Adicionar ou modificar competência pai</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            @if($competence->parent == null)
                                                Esta competência não possui uma competência pai.
                                            @else
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('competences.show', $competence->parent->id) }}" class="btn btn-default" role="button">{{$competence->parent->name}}</a>
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('competence-parent', $competence->id) }}" method="POST">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button class="btn btn-link "><span class="glyphicon glyphicon-remove"></span></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td>
                                            <div class=" search-competence-div">
                                                <div class="row col-xs-6 col-md-6">
                                                    <div class="input-group stylish-input-group input-append ">
                                                        <input type="text" name="search_competence" class="form-control"
                                                        placeholder="Buscar competência" id="search_parent_competence">
                                                        <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-search"></span></span>
                                                        </div>

                                                    </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <form class="col-xs-offset-1" id="deleteCompetencesForm" role="form" method="POST" action="{{ route('competences.destroy', $competence->id) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<button class="btn btn-danger">Excluir Competência</button>
						</form>
					</div>
                </div>
        </div>
    </div>
@endsection