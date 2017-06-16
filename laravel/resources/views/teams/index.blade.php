@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Equipes</div>

                <div class="panel-body">
                    @if (count($teams) > 0)
                        <table class="table table-striped task-table" id="showCompetencesTable">


                            <!-- Table Body -->
                            <tbody>
                            @foreach ($teams as $team)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text">
                                        <div><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        Não há equipes para exibição.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div align="center">
        {{$teams->render()}}
    </div>
@endsection