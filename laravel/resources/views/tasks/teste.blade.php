@extends('layouts.app')
@section('content')
    <!-- Latest Sortable -->
    <script src="http://rubaxa.github.io/Sortable/Sortable.js"></script>

    <!-- Simple List -->
    <div style="text-align:center;" class="row">
        <div class="layer title col-md-3 col-md-offset-1">Candidatos
        </div>
        <div class="layer title col-md-3 col-md-offset-1">Membros da equipe
        </div>
        <div class="layer title col-md-2 col-md-offset-1">Competências
        </div>

        <ul id="teamCandidates" class="list-group col-md-3 col-md-offset-1" style="background-color: white; min-height: 40px;max-width: 50%; padding:0;">
            @php ($taskCandidatesInfo = $task->getFinalRankAndExplanations())
            @if (count($taskCandidatesInfo["candidates"]) > 0)
                @foreach ($taskCandidatesInfo["candidates"] as  $candidate)
                    <li class="list-group-item" data-member-source="candidate" accesskey="{{$candidate->id}}">
                        {{$candidate->name}} -> {{$taskCandidatesInfo["ranking"][$candidate->id]}}
                        <span class="glyphicon glyphicon-info-sign" data-toggle = "tooltip" data-placement = "top" title="
                            @foreach ($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"]["competence"] as $competence)
                        {{$competence->name}}  <br></span>
                            @endforeach
                                "></span>
                    </li>
                @endforeach
            @endif
        </ul>
        <ul id="candidateTeam" class="list-group col-md-3 col-md-offset-1" style="background-color: white; min-height: 40px;max-width: 50%; padding:0;">
        </ul>
        <div class="col-md-3 col-md-offset-1" style=" max-width: 50%;min-height: 40px; padding:0;">
            <div class="row">
                <table class="table col-md-9" style="text-align:center; background-color: white; max-width: 50%;min-height: 40px; padding:0;">
                    <!-- Table Body -->
                    <tbody>
                        @if (count($task->competencies) > 0)
                            @foreach ($task->competencies as $competence)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text task-competence">
                                        <div><a href="{{ route('competences.show', $competence->id) }}">{{ $competence->name }}</a></div>
                                    </td>
                                    <td>
                                        <span accesskey="{{$competence->id}}" class="glyphicon glyphicon-ok competence_status unfulfilled-competency"></span>
                                    </td>
                                </tr>

                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>

        </div>


    </div >
    @include('tasks.search-team-candidate')

    <script>
        var fulfilledCompetencies = [];
        var allTaskCompetenciesIds =  {!!  json_encode($task->competencies()->pluck('competencies.id')->toArray())  !!};
        var candidateContributions = {!!  json_encode($task->allCandidates()["candidatesContribution"])  !!};
    // Simple list
    Sortable.create(teamCandidates, { group: "taskTeam",
        sort: false});
    Sortable.create(candidateTeam, { group: "taskTeam",
        filter: '.js-remove, .user-entered-member',
        onFilter: function (evt) {
            var item = evt.item,
                ctrl = evt.target;

            if (Sortable.utils.is(ctrl, ".js-remove")) {  // Click on remove button
                item.parentNode.removeChild(item); // remove sortable item
            }
        },
        onRemove: function (/**Event*/evt) {
            //var id = $( evt.item).attr('accessKey');
            fulfilledCompetencies = [];
            var listItems = $("#candidateTeam li");
            listItems.each(function(candidate) {
                var id = $(this).attr('accessKey');
                var source =  $(this).attr('data-member-source');
                if (source !== "userEnteredCandidate") {
                    $.each(candidateContributions[id]["competenceInfo"]["competence"], function (i, elem) {
                        fulfilledCompetencies.push(String(elem.id));
                    });
                }

            });
            updateCompetenciesFullfilment();
        },
        onAdd: function (/**Event*/evt) {
            //id do usuario
            var id = $( evt.item).attr('accessKey');
            var source = $( evt.item).attr('data-member-source');
            console.log("source: "+source);
            if (source !== "userEnteredCandidate") {
                $.each(candidateContributions[id]["competenceInfo"]["competence"], function (i, elem) {
                    fulfilledCompetencies.push(String(elem.id));
                });
                updateCompetenciesFullfilment();
            }

        },
    } );
    function updateCompetenciesFullfilment() {
        $('.competence_status').each(function(i, obj) {
            var competenceId = $(this).attr('accesskey');
            if(jQuery.inArray(competenceId, fulfilledCompetencies) !== -1) {
                $(this).removeClass( "unfulfilled-competency" ).addClass( "fulfilled-competency" );
            } else {
                $(this).removeClass( "fulfilled-competency" ).addClass( "unfulfilled-competency" );
            }
        });
    }


    </script>
    <script src="{{ asset('js/team-task-recomendation.js') }}"></script>
    <!-- form start -->
    <form class="form-horizontal" id="createTeamForm"role="form" method="POST" action="{{ route('tasks.store-team') }}">
        {{ csrf_field() }}
        <input type="hidden" class="form-control" name="task_id" value="{{$task->id}}">
            <div class="form-group col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Cadastrar equipe</button>
            </div>
        </div>
        <!-- /.panel-body -->
    </form>
@endsection
