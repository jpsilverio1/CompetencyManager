
<div class="panel panel-default col-md-4 col-md-offset-1">
    <div class="panel-heading" >
        Equipes sugeridas
    </div>
    <div class="panel-body">
                <?php $suitableAssigneesForTask = $taskCandidatesInfo["novaRecomendacao"]["finalResult"]; ?>
                @if (count($suitableAssigneesForTask) > 0)
                        <table class="table table-striped" id="team-suggestion-table">
                            <!-- Table Headings -->
                            <thead >
                            <th >Equipe</th>
                            <th >&nbsp;</th>
                            </thead>
                            <!-- Table Body -->
                            <tbody>
                            @foreach ($suitableAssigneesForTask as $index =>$users)
                                <tr accesskey="{{$index}}">
                                    <td class="candidatos">
                                        <ul class="suggested-task-team-members">
                                            @foreach($users as $user)
                                                <li><a accesskey="{{$user->id}}" href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></li>
                                            @endforeach

                                        </ul>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary add-candidates-to-team">Adicionar usuários à equipe</button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                @else
                    Não há usuários aptos a realizar esta tarefa
                @endif
    </div>
</div>
<script>


    $(document).ready(function () {
        $("#team-suggestion-table").on('click', '.add-candidates-to-team', function () {
            $(this).closest('tr').find('td:first').children('.suggested-task-team-members').find('li a').each(function(i, obj) {
                var userId = $(this).attr('accesskey');
                $("#candidateTeam").append($('#teamCandidates li[accesskey="'+userId+'"]'));
                $.each(candidateContributions[userId]["competenceRep"], function (i, elem) {
                    fulfilledCompetencies.push(String(elem));
                });

            });
            updateCompetenciesFullfilment();
        });

    });
</script>
