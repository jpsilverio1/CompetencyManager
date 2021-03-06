
    <!-- Latest Sortable -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <div class="panel panel-info task-team-creation-assistant-panel">
        <div class="panel-heading" >
            Assistente de criação de equipes
        </div>
        <div class="panel-body">
            @if ($errors->has('team'))
                <div class="alert alert-warning">
                    {{$errors->first('team')}}<br />
                </div>
        @endif
    <!-- Simple List -->
    <div  class="row">
        <div class="layer title col-md-3 col-md-offset-1">Candidatos
        </div>
        <div class="layer title col-md-3 col-md-offset-1">Membros da equipe
        </div>
        <div class="layer title col-md-1 col-md-offset-1">Competências
        </div>
        <div >
            <ul id="teamCandidates" class="list-group col-md-3 col-md-offset-1" style="background-color: white; min-height: 40px;max-width: 50%; padding:0;">
                @php ($taskCandidatesInfo = $task->getFinalRankAndExplanations())

                @if (count($taskCandidatesInfo["candidates"]) > 0)
                    @foreach ($taskCandidatesInfo["candidates"] as  $candidate)
                        <li class="list-group-item" data-member-source="candidate" accesskey="{{$candidate->id}}" type="display: flex; justify-content: space-around;">
                            <span class="ranking-circle">{{$taskCandidatesInfo["candidateRanking"][$candidate->id]}}</span>
                            {{$candidate->name}}
                            <div class="glyphicon glyphicon-info-sign task-team-creation-user-popover" data-container="body" data-toggle = "popover" data-placement = "right"  data-html="true"
                                 data-content="
                              <h4>Resumo </h4>
                              <ul class='sub-competence'>
                              <li> Ranking geral do candidato: {{$taskCandidatesInfo["candidateRanking"][$candidate->id]}}
                                         </li>
                                         <li> Número médio de endossos das competências exigidas pela tarefa:
{{ $taskCandidatesInfo["rankingData"]["individualRankingValues"]["number of endorsements"][$candidate->id] }}
                                         </li>
                                          <li>  Nível médio de colaboração:
{{ number_format($taskCandidatesInfo["rankingData"]["individualRankingValues"]["collaborative competencies"][$candidate->id]*100,2) }}%
                             </li>
                             <li> Nível médio de lembrança das competências exigidas pela tarefa:
                                 {{ $taskCandidatesInfo["rankingData"]["individualRankingValues"]["remembering level"][$candidate->id] }}%
                             </li>
                             <li> Número de competências que o usuário possui e são exigidas pela tarefa:
                                {{ $taskCandidatesInfo["rankingData"]["individualRankingValues"]["number of competencies"][$candidate->id] }}
                                         </li>
                                         <li> Número de competências que o usuário possui num nivel igual ou superior ao exigido pela tarefa:
{{ $taskCandidatesInfo["rankingData"]["individualRankingValues"]["number of competencies in acceptable level"][$candidate->id] }}
                                         </li>
                                          </ul>

                                          <h4> Descrição detalhada </h4>
@foreach($task->competencies as $taskCompetence)
                                 @if(array_key_exists($taskCompetence->id, $taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"]))
                                         <hX><b>{{$taskCompetence->name}}</b/></hX>
                              <ul class='sub-competence'>
                              @foreach($taskCandidatesInfo["candidatesContribution"][$candidate->id]["competenceInfo"][$taskCompetence->id]["competence"] as $index => $candidateCompetence)
                                         <li>
                         <i class='fa fa-circle acceptableCompetenceLevel-{{$taskCandidatesInfo["individualCandidateValues"][$candidate->id]["number of competencies in acceptable level"][$candidateCompetence->id]}}' aria-hidden='true'></i>
                {{$candidateCompetence->name}} -
                {{$candidateCompetence->pivot->proficiency_level_name}} -
                {{$taskCandidatesInfo["individualCandidateValues"][$candidate->id]["remembering level"][$candidateCompetence->id]}} -
                {{$taskCandidatesInfo["individualCandidateValues"][$candidate->id]["number of endorsements"][$candidateCompetence->id]}}
                                         </li>
@endforeach

                                         </ul>
@endif
                                 @endforeach

                                         "></div>
                        </li>
                    @endforeach
                @else
                    Não há candidatos que possuam as competências requeridas por esta tarefa mas você ainda pode adicionar candidatos manualmente!
                @endif

            </ul>
        </div>


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
                            <div><a href="{{ route('competences.show', $competence->id) }}">{{ $competence->name }}</a> - {{$competence->pivot->proficiency_level_name}}</div>
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
            <div class="row">
                <div >
                    @include('tasks.search-team-candidate')
                </div>
                <div >
                    @include('tasks.task-team-recommendation')
                </div>
            </div>



<script>

var fulfilledCompetencies = [];
var allTaskCompetenciesIds =  {!!  json_encode($task->competencies()->pluck('competencies.id')->toArray())  !!};
var candidateContributions = {!!  json_encode($task->allCandidates()["candidatesContribution"])  !!};

</script>
<script src="{{ asset('js/team-task-recomendation.js') }}"></script>
<!-- form start -->
<form class="form-horizontal" id="createTeamForm"role="form" method="POST" action="{{ route('tasks.store-team') }}">
{{ csrf_field() }}
<input type="hidden" class="form-control" name="task_id" value="{{$task->id}}">
<div class="form-group col-md-12 text-center">
    <button type="submit" class="btn btn-primary">Cadastrar equipe</button>
</div>
</form>

</div>

</div>
