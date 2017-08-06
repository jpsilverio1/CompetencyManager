    <div class="panel panel-default">
        <div class="panel-heading">Competências de {{$user->name}}</div>

        <div class="panel-body">
            @if (count($competences) > 0)
                <table class="table table-striped task-table" id="showCompetencesTable">
                    <!-- Table Headings -->
                    <thead>
                    <th>Competência</th>
                    <th>Nível</th>
                    <th>&nbsp;Número de endossos</th> <!-- number of endorsements -->
                    <th>&nbsp;</th> <!--endorsement status -->
                    @if ($showEndorsementSection)
                        <th>Endossar</th>
                    @endif
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                    @foreach ($competences as $competence)
                        <?php $numberOfEndorsementsForCompetence = $user->getNumberOfEndorsementsForCompetence($user->endorsements(),$competence); ?>

                        <tr>
                            <form action="/user-endorsements" method="POST">
                            {{ csrf_field() }}
                            <td class="table-text">
                                <input type="hidden" name="endorsed_user_id" value="{{$user->id}}" />
                                <input type="hidden" name="competence_id" value="{{$competence->id}}" />
                                <div>{{ $competence->name }}</div>

                            </td>
                            <td class="table-text">
                                <div>{{ $competence->pivot->competence_level }}</div>

                            </td>

                            <td>
                                <div class="btn btn-info btn-circle">{{$numberOfEndorsementsForCompetence}}</div>
                                    @if ($numberOfEndorsementsForCompetence >0)
                                    <div class="most-endorsed-level-percentage">
                                        <?php $result = $user->computeThings($competence->id); ?>
                                        {{$result[0]}}%
                                    @foreach ($result[1] as $maximumKey)
                                        {{$maximumKey}}
                                    @endforeach
                                </div>
                                 @endif
                            </td>
                            <td>
                                <div class="col-md-8">
                                    @if($user->loggedUserEndorsedCompetence($user->endorsements(),$competence->id) > 0)
                                        Você endossou essa competência no nível
                                        <em>
                                            {{$user->getEndorsementLevel($competence->id)}}

                                        </em>.
                                        <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Você pode atualizar o nível de seu endosso mudando o nível e clicando em  endossar ao lado."></span>
                                    @endif
                                </div>
                            </td>
                                @if ($showEndorsementSection)
                                    <td class="col-md-3">
                                        <div class="competency_level">
                                            <span class="competence_level_label" name="levels">Básico</span>
                                            <input type="range" class="competence_level_slider"
                                            name="rangeInput" min="1" max="3" value ="1" onchange="updateTextInput(this);">
                                        </div>
                                        <input type="hidden" class="competence_level_class" name="competence_level" value="Básico" />
                                        <div class="form-group">
                                            <div class=" col-sm-1">
                                                <button type="submit" class="btn btn-primary"> Endossar</button>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                Você ainda não cadastrou nenhuma competência.
            @endif
        </div>
    </div>

<script>
    function getLabelForSliderValue(val) {
        if (val == 1) {
            return "Básico";
        }
        if (val == 2) {
            return "Intermediário";
        }
        if (val == 3) {
            return "Avançado";
        }
    }
    function updateTextInput(slider) {
        var rowHit = $(slider).parent().parent().parent();
        var sliderLabel = rowHit.find(".competence_level_label");
        var newLabel = getLabelForSliderValue(slider.value);
        sliderLabel.html(newLabel);
        var competenceLevelInputField = rowHit.find(".competence_level_class");
        competenceLevelInputField.val(newLabel);
    }
    $(document).ready(function () {
        //information tooltip
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>