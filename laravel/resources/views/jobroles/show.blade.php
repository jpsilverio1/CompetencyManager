@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$jobrole->name}}
                </h2>
            </div>
            <div class="panel-body">
				@if (!empty($message) > 0)
                    <div class="alert alert-success">
                        {{$message}}<br />
						</div>
                @endif
                @if (Session::has('message'))
                   <div class="alert alert-success">
                      {{Session::get('message')}}<br />
                   </div>
                @endif
                <h4>
                    Descrição
                </h4>
                <p>{{$jobrole->description}}</p>

                <div class="panel panel-default">
                        <div class="panel-heading" >
                            Usuários que cumprem as exigências deste Cargo
                        </div>
                        <div class="panel-body">

                            <?php $suitableAssigneesForJobRole = $jobrole->suitableAssigneesSets(); ?>
                                @if (count($suitableAssigneesForJobRole) > 0)
                                    <ul>
										@foreach($suitableAssigneesForJobRole as $user)
											<li><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></li>
										@endforeach
                                    </ul>
								@else
									Não há usuários aptos para este cargo.
								@endif
                        </div>
                    </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências requeridas para este Cargo
                        @include('competences.show_paginated_competences', ['competences' => $jobrole->competencies()->paginate(5, ['*'],'competences'),
                        'showCompetenceLevel' => False,
                        'showDeleteButton' => False,
                        'useCompetency' => True,
                        'noCompetencesMessage' => 'Não há competências para exibição.'])
                    </div>
                </div>

                    <div>
                        <div class="col-md-2">
                            <td><a href='{{ route('jobroles.edit', $jobrole->id) }}'/><button type="submit" class="btn btn-primary">Editar</button></td>
                        </div>
                        <div>
                            <form class="col-xs-offset-1" id="deleteJobRoleForm" role="form" method="POST" action="{{ route('jobroles.destroy', $jobrole->id ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE" />
                                <td><button class="btn btn-danger">Excluir</button></td>
                            </form>
                        </div>
                    </div>
				
            </div>
        </div>
    </div>
@endsection
