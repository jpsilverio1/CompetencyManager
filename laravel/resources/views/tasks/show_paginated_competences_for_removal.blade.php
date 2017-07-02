<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Competencias requeridas pela tarefa</div>

        <div class="panel-body">
            @include('competences.show_paginated_competences', ['competences' => $task->competencies()->paginate(5, ['*'],'competences'),
            'showCompetenceLevel' => True,
            'showDeleteButton' => True,
            'noCompetencesMessage' => 'Você ainda não cadastrou competências.',
             'path_to_removal' => '/task-competence/'.$task->id.'/'])

        </div>
    </div>
</div>