<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Tarefas das quais você faz parte</div>

        <div class="panel-body">
            @include('tasks.show_paginated_tasks', ['tasks' => Auth::user()->joinedTasks()->paginate(5, ['*'],'tasks'), 'noTasksMessage' => 'Você ainda não faz parte de nenhuma tarefa'])
        </div>
    </div>
</div>