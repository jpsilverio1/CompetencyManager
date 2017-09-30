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
                        @if (Session::has('message'))
                            <div class="alert alert-success">
                                {{Session::get('message')}}<br />
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
									<td><a href='{{ route('competences.edit', $competence->id) }}'/><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>
									
									<form class="col-xs-offset-1" id="deleteCompetencesForm" role="form" method="POST" action="{{ route('competences.destroy', $competence->id ) }}">
										{{ csrf_field() }}
										<input type="hidden" name="_method" value="DELETE" />
										<td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
									</form>
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