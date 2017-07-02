@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
                <div class="panel panel-fullScreen">
                    <div class="panel-heading">Editar equipe</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="editTeamForm" role="form" method="POST" action="{{ route('teams.update', ['id' => $team->id]) }}">
                            {{ csrf_field() }}
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    Houve algum problema ao editar a equipe.<br />
                                </div>
                            @endif
							<table class="table table-striped team-table" id="editTeamTable">
                                <tbody>
                                <tr>
									<input type="hidden" name="_method" value="put" />
									<input type="hidden" name="id" value="{{ $team->id }}" />
                                    <td class="form-group  col-md-5{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-1 control-label">Equipe</label>
                                        <div class=" col-md-offset-4">
                                            <input type="text" class="form-control" name="name" placeholder="Nome da equipe"  value="{{ old('name', $team->name) }}">
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                            @endif
                                        </div>

                                    </td>
                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description') ? ' has-error' : '' }}">
                                        <div class="">
                                            <input type="text" class="form-control" name="description" placeholder="Descrição da equipe" value="{{ old('description', $team->description) }}">
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
                            <div class="row col-xs-offset-1">
                                @include('users.add_users_without_button')
                            </div>
                            <div class="form-group">
                                <div class="col-xs-5 col-xs-offset-1">
                                    <button type="submit" class="btn btn-primary">Salvar Equipe</button>
                                </div>
                            </div>
                        </form>
                        <div class="row col-xs-offset-1">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Membros da equipe</div>

                                    <div class="panel-body">
                                        @include('users.show_paginated_users', ['users' => $team->teamMembers()->paginate(5, ['*'],'teams'),
                            'showDeleteButton' => True,
                            'path_to_removal' => '/team-member/'.$team->id.'/'])

                                    </div>
                                </div>
                            </div>
                        </div>

						<form class="col-xs-offset-1" id="deleteTeamsForm" role="form" method="POST" action="{{ route('teams.destroy', ['id' => $team->id] ) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE" />
							<input type="hidden" name="id" value="{{ $team->id }}" />       
							<button type="" class="btn btn-danger">Excluir Equipe</button>
						</form>
                    </div>
                </div>
        </div>
    </div>
@endsection