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


