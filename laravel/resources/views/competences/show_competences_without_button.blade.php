<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">Competências de {{$team->name}}</div>

        <div class="panel-body">
            @if (count($competences) > 0)
                <table class="table table-striped task-table" id="showCompetencesTable">
                    <!-- Table Headings -->
                    <thead>
                    <th>Competência</th>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                    @foreach ($competences as $competence)
                        <tr>
                            <td class="table-text">
                                <input type="hidden" name="competence_id" value="{{$competence->id}}" />
                                <div>{{ $competence->name }}</div>
								
								@if($showDeleteButton)
									<td>
										<form action="/user-competency/{{ $competence->id }}" method="POST">
											{{ csrf_field() }}
											{{ method_field('DELETE') }}

											<button>x</button>
										</form>
									</td>
								@endif

                            </td>
                            <td class="table-text">
                                <div>{{ $competence->pivot->competency_level }}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                Nenhuma competência cadastrada para esta equipe.
            @endif
        </div>
    </div>
</div>
