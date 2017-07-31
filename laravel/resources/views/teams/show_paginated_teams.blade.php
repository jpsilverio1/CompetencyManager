<table class="table table-striped task-table" id="showCompetencesTable">


    <!-- Table Body -->
    <tbody>
    @if (count($teams) > 0)
        @foreach ($teams as $team)
            <tr>
                <!-- Task Name -->
                <td class="table-text">
                    <div><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></div>
                </td>
				<td><a href="{{ route('teams.edit', $team->id) }}"/><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>
									
				<form class="col-xs-offset-1" id="deleteTeamsForm" role="form" method="POST" action="{{ route('teams.destroy', ['id' => $team->id] ) }}">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="DELETE" />
					<input type="hidden" name="id" value="{{ $team->id }}" />
					<td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
				</form>
            </tr>
        @endforeach

    </tbody>
</table>
<div align="center">
    {{$teams->render()}}
</div>
    @else
        <tr>
            <td class="table-text">
                Não há equipes para exibição.
            </td>

        </tr>
    </tbody>
</table>
    @endif


