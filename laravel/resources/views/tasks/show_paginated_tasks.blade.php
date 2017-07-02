<table class="table table-striped task-table" id="showCompetenceTable">
    <!-- Table Body -->
    <tbody>
        @if (count($tasks) > 0)
            @foreach ($tasks as $task)
                <tr>
                    <!-- Task Name -->
                    <td class="table-text">
                        <div><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a></div>
                    </td>
					
					<td><a href="{{ route('tasks.edit', $task->id) }}"/><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>
									
					<form class="col-xs-offset-1" id="deleteTaskForm" role="form" method="POST" action="{{ route('tasks.destroy', ['id' => $task->id] ) }}">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="DELETE" />
						<input type="hidden" name="id" value="{{ $task->id }}" />       
						<td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
					</form>
                </tr>
            @endforeach
    </tbody>
</table>
            <div align="center">
                {{$tasks->render()}}
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


