<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Tarefas criadas por você</div>

        <div class="panel-body">
            @include('tasks.show_paginated_tasks', ['tasks' => Auth::user()->createdTasks()->paginate(1, ['*'],'tasks'), 'noTasksMessage' => 'Você ainda não criou nenhuma tarefa'])
        </div>
    </div>
</div>