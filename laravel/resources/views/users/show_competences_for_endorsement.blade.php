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
        $('.competence_level_slider').each(function (i, obj) {
            //console.log(obj.max);
            obj.max = numberOfCategories;
        });
        //information tooltip
        $('[data-toggle="tooltip"]').tooltip();
        //popover
        $(function () {
            $(".pop").popover({trigger: "manual", html: true, animation: false})
                .on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show");
                    $(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                var _this = this;
                setTimeout(function () {
                    if (!$(".popover:hover").length) {
                        $(_this).popover("hide");
                    }
                }, 300);
            });
        });
    });
</script>
<div class="panel panel-default">
    <div class="panel-heading">Competências de {{$user->name}}</div>

    <div class="panel-body">
        @if (count($competences) > 0)
            <table class="table table-striped task-table" id="showCompetencesTable">
                <!-- Table Headings -->
                <thead >
                <th >Competência</th>
                <th >Nível</th>
                <th style="text-align: center">&nbsp;Número de endossos</th> <!-- number of endorsements -->
                <th style="text-align: center" class="col-md-3">&nbsp;</th> <!--endorsement status -->
				<th style="text-align: center" >Nível de Lembrança</th>

                @if ($showEndorsementSection)
                    <th style="text-align: center">Endossar</th>
                @endif
                </thead>

                <!-- Table Body -->
                <tbody>
                @foreach ($competences as $competence)

                    @php($forgettingLevel = $user->forgettingLevel($competence))
                    @php($numberOfCompetenceLevels = \App\CompetenceProficiencyLevel::count())
                    @php($step = $numberOfCompetenceLevels/5)
                    <?php $numberOfEndorsementsForCompetence = $user->getNumberOfEndorsementsForCompetence($user->endorsements(), $competence); ?>
                    @php($numberOfEndorsementsPerLevel = $user->getNumberOfEndorsementsPerLevelForCompetence($competence, $user))

                    @php($endorsersPerLevel = $user->getEndorsersPerLevel($user,$competence))
                    <tr>
                        <form action="/user-endorsements" method="POST">
                            {{ csrf_field() }}
                            <td class="table-text">
                                <input type="hidden" name="endorsed_user_id" value="{{$user->id}}"/>
                                <input type="hidden" name="competence_id" value="{{$competence->id}}"/>
                                <div><a href="{{ route('competences.show', $competence->id) }}">{{ $competence->name }}</a></div>

                            </td>
                            <td class="table-text">
                                <div> {{\App\CompetenceProficiencyLevel::findOrFail($competence->pivot->competence_proficiency_level_id)->name}}</div>
                            </td>
    <td>
        <div style="text-align: center">
            @php($levelIndex = 0)
            @foreach($numberOfEndorsementsPerLevel as $proficiencyLevelId => $numberOfEndorsementsForProficiencyLevel)
                @php($colorIndex =  ceil($levelIndex/$step))
                <div class="btn btn-info btn-circle pop profiency-level-button-{{$colorIndex}}" data-toggle="popover"
                     data-html="true"
                     data-position="relative" data-container="body"
                     title='{{$numberOfEndorsementsForProficiencyLevel["proficiencyLevelName"]}}'
                     data-content="
                                             @foreach($numberOfEndorsementsForProficiencyLevel["endorsers"] as $endorser)
                             <a href='{{ route('users.show', $endorser->id) }}'>
                                                   <button type='button' class='btn btn-info btn-circle'>
                                                    {{$endorser->getInitialsFromName()}}
                             </button>
                             </a>
@endforeach
                             ">{{$numberOfEndorsementsForProficiencyLevel["endorsementPerLevel"]}} </div>
                @php($levelIndex = $levelIndex + 1)
            @endforeach
        </div>
    </td>
    <td>
        <div>
            @if($user->loggedUserEndorsedCompetence($user->endorsements(),$competence->id) > 0)
                Você endossou essa competência no nível
                <em>
                    {{$user->getEndorsementLevel($competence->id)}}

                </em>.
                <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip"
                      title="Você pode atualizar o nível de seu endosso mudando o nível e clicando em  endossar ao lado."></span>
            @endif
        </div>
    </td>
    <td>
        <div style="text-align: center">
            {{ $forgettingLevel}}%
        </div>
    </td>
    @if ($showEndorsementSection)
        <td class="col-md-3">
            <div class="competency_level">
                <span class="competence_level_label" name="levels"><script>document.write(getLabelForSliderValue(1));</script></span>
                <input type="range" class="competence_level_slider"
                       name="competence_proficiency_level" min="{{\App\CompetenceProficiencyLevel::min('id')}}" max="{{\App\CompetenceProficiencyLevel::max('id')}}" value="{{\App\CompetenceProficiencyLevel::min('id')}}"
                       onchange="updateTextInput(this);">
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
        Não há competências para exibição.
    @endif
</div>
</div>

