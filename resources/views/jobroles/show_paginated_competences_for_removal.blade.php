<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Competências requeridas pelo Cargo</div>

        <div class="panel-body">
            @include('competences.show_paginated_competences', ['competences' => $jobrole->competencies()->paginate(5, ['*'],'competences'),
            'showCompetenceLevel' => True,
            'showDeleteButton' => True,
            'noCompetencesMessage' => 'Você ainda não cadastrou competências.',
             'path_to_removal' => '/jobrole-competency/'.$jobrole->id.'/'])

        </div>
    </div>
</div>