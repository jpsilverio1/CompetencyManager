@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-fullScreen">
                <div class="panel-heading"><h3>Adicionar nova equipe</h3></div>
                <div class="panel-body">
                    {{var_dump(old())}}
                    <form class="form-horizontal" id="addTeamForm"role="form" method="POST" action="{{ route('teams.store') }}">
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
                        @include('competences.add_competences_without_button', ['showCompetenceLevel' => False])
                        @include('users.add_users_without_button')
                        <div class="form-group">
                            <div class="col-xs-5 col-xs-offset-1">
                                <button type="submit" class="btn btn-primary">Cadastrar Equipe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection