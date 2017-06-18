@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Competências</div>

                <div class="panel-body">

                    @if (!empty($message) > 0)
                        <div class="alert alert-success">
                            {{$message}}<br />
                        </div>
                    @endif
                    @if (count($competences) > 0)
                        <table class="table table-striped task-table" id="showCompetencesTable">


                            <!-- Table Body -->
                            <tbody>
                            @foreach ($competences as $competence)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text">
                                        <div><a href="{{ route('competences.show', $competence->id) }}">{{ $competence->name }}</a></div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        Não há competências para exibição.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div align="center">
        {{$competences->render()}}
    </div>
@endsection