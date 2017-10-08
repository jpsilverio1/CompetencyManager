@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$learningAid->name}}
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
                <p>{{$learningAid->description}}</p>

                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Usuários aptos para aplicar o treinamento
                    </div>
                    <div class="panel-body">

                        <?php $suitableAssigneesForLearningAid = $learningAid->suitableAssigneesSets(); ?>
                        @if (count($suitableAssigneesForLearningAid) > 0)
                            <ul>
                                @foreach($suitableAssigneesForLearningAid as $users)
                                    <li> Grupo
                                        <ul>
                                            @foreach($users as $user)
                                                <li><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></li>
                                            @endforeach

                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            Não há usuários aptos para aplicar o treinamento
                        @endif
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Competências requeridas pelo treinamento
                        @include('competences.show_paginated_competences', ['competences' => $learningAid->competencies()->paginate(5, ['*'],'competences'),
                        'showCompetenceLevel' => True,
                        'showDeleteButton' => False,
                        'useCompetency' => False,
                        'noCompetencesMessage' => 'Não há competências para exibição.'])
                    </div>
                </div>
                @if (Auth::user()->isManager())
                <div>
                    <div class="col-md-2">
                        <td><a href='{{ route('learningaids.edit', $learningAid->id) }}'/><button type="submit" class="btn btn-primary">Editar</button></td>
                    </div>
                    <div>
                        <form class="col-xs-offset-1" id="deleteLearningAidForm" role="form" method="POST" action="{{ route('learningaids.destroy', $learningAid->id ) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE" />
                            <td><button class="btn btn-danger">Excluir</button></td>
                        </form>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection