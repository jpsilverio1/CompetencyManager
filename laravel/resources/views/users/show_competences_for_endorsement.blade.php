<script>
    var dictionary;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = "{{ route('competence-proficiency-level') }}";
    dictionary = function () {
        var tmp = null;
        $.ajax({
            'async': false,
            'type': "GET",
            'global': false,
            'url': url,
            'success': function (data) {
                tmp = data;
            }
        });
        return tmp;
    }();
    function getLabelForSliderValue(val) {
        return dictionary[val];
    }
    function updateTextInput(slider) {
        var rowHit = $(slider).parent().parent().parent();
        var sliderLabel = rowHit.find(".competence_level_label");
        var newLabel = getLabelForSliderValue(slider.value);
        sliderLabel.html(newLabel);
    }
    $(document).ready(function () {

        var numberOfCategories = {{\App\CompetenceProficiencyLevel::count()}}
        $('.competence_level_slider').each(function(i, obj) {
            //console.log(obj.max);
            obj.max = numberOfCategories;
        });
        //information tooltip
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<div class="panel panel-default">
    <div class="panel-heading">Competências de {{$user->name}}</div>

    <div class = "panel-body">
        @if (count($competences) > 0)
            <table class="table table-striped task-table" id="showCompetencesTable">
                <!-- Table Headings -->
                <thead>
                <th>Competência</th>
                <th>Nível</th>
                <th>&nbsp;Número de endossos</th><!-- number of endorsements -->
                <th>&nbsp;</th> <!--endorsement status -->
                @if ($showEndorsementSection)
                    <th>Endossar</th>
                @endif
                </thead>

                <!-- Table Body -->
                <tbody>
                @foreach ($competences as $competence)
                    <?php $numberOfEndorsementsForCompetence = $user->getNumberOfEndorsementsForCompetence($user->endorsements(),$competence); ?>
                    @php($ola = $user->getNumberOfEndorsementsPerLevelForCompetence($competence, $user))

                    <tr>
                        <form action="/user-endorsements" method="POST">
                            {{ csrf_field() }}
                            <td class="table-text">
                                <input type="hidden" name="endorsed_user_id" value="{{$user->id}}" />
                                <input type="hidden" name="competence_id" value="{{$competence->id}}" />
                                <div>{{ $competence->name }}</div>

                            </td>
                            <td class="table-text">
                                <div> {{\App\CompetenceProficiencyLevel::findOrFail($competence->pivot->competence_proficiency_level_id)->name}}</div>
                            </td>

                            <td>
                                <div>
                                @foreach($ola as $proficiencyLevelId => $numberOfEndorsementsForProficiencyLevel)
                                        <div class="btn btn-info btn-circle" data-toggle="tooltip" title='{{$numberOfEndorsementsForProficiencyLevel["proficiencyLevelName"]}}'>{{$numberOfEndorsementsForProficiencyLevel["ola"]}}</div><!--</td>-->
                                @endforeach
                                </div>
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
                                        <span class="competence_level_label" name="levels"><script>document.write(getLabelForSliderValue(1));</script></span>
                                        <input type="range" class="competence_level_slider"
                                               name="competence_proficiency_level" min="1" max="3" value ="1" onchange="updateTextInput(this);">
                                    </div>
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

