<table class="table table-striped task-table" id="showCompetencesTable">


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
            <div align="center">
                {{$tasks->render()}}
            </div>
        @else
            <tr>
                <td class="table-text">
                    Não há tarefas para exibição.
                </td>

            </tr>

        @endif
    </tbody>
    </table>

