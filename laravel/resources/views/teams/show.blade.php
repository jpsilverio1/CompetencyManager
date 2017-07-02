@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$team->name}}
                </h2>
            </div>
            <div class="panel-body">
                @if (!empty($message) > 0)
                    <div class="alert alert-success">
                        {{$message}}<br />
                    </div>
                @endif
                <h4>
                    Descrição
                </h4>
                <p>{{$team->description}}</p>

                
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências para esta Equipe
                        @include('competences.show_paginated_competences', ['competences' => $team->competencies() ->paginate(5, ['*'],'competences'),
                        'showCompetenceLevel' => False,
                        'showDeleteButton' => False,
                        'noCompetencesMessage' => 'Não há competências para exibição.'])
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Integrantes desta equipe
                        @include('users.show_paginated_users', ['users' => $team->teamMembers()->paginate(5, ['*'],'teams'),
                        'showDeleteButton' => False])
                    </div>
                </div>
                    <div>
                        <div class="col-md-2">
                            <a href="{{ route('teams.edit', $team->id) }}"/><button type="submit" class="btn btn-primary">Editar Equipe</button>
                        </div>
                        <div>
                            <form class="col-xs-offset-1" id="deleteTeamForm" role="form" method="POST" action="{{ route('teams.destroy', ['id' => $team->id] ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE" />
                                <input type="hidden" name="id" value="{{ $team->id }}" />
                                <td><button type="" class="btn btn-danger">Excluir Equipe</button></td>
                            </form>
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection
