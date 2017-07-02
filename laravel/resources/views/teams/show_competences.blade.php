    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Competências desta equipe</div>

            <div class="panel-body">
                    @include('competences.show_team_paginated_competences', ['competences' => $team->competencies()->paginate(5, ['*'],'competences'),
                    'showCompetenceLevel' => False,
                    'showDeleteButton' => True,
                    'noCompetencesMessage' => 'Não há competências cadastradas para esta Equipe..' ])

            </div>
        </div>
    </div>