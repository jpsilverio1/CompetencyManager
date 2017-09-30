@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-fullScreen">
                    <div class="panel-heading">Editar um Cargo</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="editJobRoleForm" role="form" method="POST" action="{{ route('jobroles.update', ['id' => $jobrole->id]) }}">
                            {{ csrf_field() }}
							<table class="table table-striped jobroles-table" id="editJobRoleTable">
                                <tbody>
                                <tr>
									<input type="hidden" name="_method" value="put" />
									<input type="hidden" name="id" value="{{ $jobrole->id }}" />
                                    <td class="form-group  col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Cargo</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="title" placeholder="Nome do Cargo"  value="{{ old('name', $jobrole->name) }}">
                                            @if ($errors->has('name'))
                                                <span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description" placeholder="Descrição do Cargo" value="{{ old('description', $jobrole->description) }}">
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
                                    <button type="submit" class="btn btn-primary">Salvar Cargo</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            @include('jobroles.show_paginated_competences_for_removal')
                        </div>
						<form class="col-xs-offset-1" id="deleteJobRolesForm" role="form" method="POST" action="{{ route('jobroles.destroy', $jobrole->id ) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<button  class="btn btn-danger">Excluir Cargo</button>
						</form>
                    </div>
                </div>
        </div>
    </div>
@endsection