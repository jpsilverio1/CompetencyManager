<table class="table table-striped task-table" id="showCompetenceTable">
    <!-- Table Body -->
    <tbody>
        @if (count($jobroles) > 0)
            @foreach ($jobroles as $jobrole)
                <tr>
                    <td class="table-text">
                        <div><a href="{{ route('jobroles.show', $jobrole->id) }}">{{ $jobrole->name }}</a></div>
                    </td>
					
					<td><a href="{{ route('jobroles.edit', $jobrole->id) }}"/><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>
									
					<form class="col-xs-offset-1" id="deleteJobRoleForm" role="form" method="POST" action="{{ route('jobroles.destroy', $jobrole->id ) }}">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="DELETE" />
						<td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
					</form>
                </tr>
            @endforeach
    </tbody>
</table>
            <div align="center">
                {{$jobroles->render()}}
            </div>
        @else
            <tr>
                <td class="table-text">
                    {{$noTasksMessage}}
                </td>

            </tr>
            </tbody>
            </table>
        @endif


