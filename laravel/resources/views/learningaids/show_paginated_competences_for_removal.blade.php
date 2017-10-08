<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Competências requeridas pelo treinamento</div>

        <div class="panel-body">
            @include('competences.show_paginated_competences', ['competences' => $learningAid->competencies()->paginate(5, ['*'],'competences'),
            'showCompetenceLevel' => True,
            'showDeleteButton' => True,
            'useCompetency' => False,
            'noCompetencesMessage' => 'Você ainda não cadastrou competências.',
             'path_to_removal' => '/learningaid-competency/'.$learningAid->id.'/'])

        </div>
    </div>
</div>