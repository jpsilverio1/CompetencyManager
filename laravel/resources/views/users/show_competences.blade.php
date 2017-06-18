    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Suas competências</div>

            <div class="panel-body">
                    @include('competences.show_paginated_competences', ['competences' => Auth::user()->competencies()->paginate(5, ['*'],'competences'),
                    'showCompetenceLevel' => True,
                    'showDeleteButton' => True,
                    'noCompetencesMessage' => 'Você ainda não cadastrou competências.' ])

            </div>
        </div>
    </div>