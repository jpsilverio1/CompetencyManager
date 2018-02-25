
<div class="panel panel-default col-md-4 col-md-offset-1">
    <div class="panel-heading" >
        Equipes sugeridas
    </div>
    <div class="panel-body">
                <?php $suitableAssigneesForTask = $task->getFinalRankAndExplanations()["novaRecomendacao"]["finalResult"]; ?>
                @if (count($suitableAssigneesForTask) > 0)
                        <table class="table table-striped" id="team-suggestion-table">
                            <!-- Table Headings -->
                            <thead >
                            <th >Equipe</th>
                            <th >&nbsp;</th>
                            </thead>
                            <!-- Table Body -->
                            <tbody>
                            @foreach ($suitableAssigneesForTask as $users)
                                <tr>
                                    <td>
                                        <ul class="suggested-task-team-members">
                                            @foreach($users as $user)
                                                <li><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></li>
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
            alert("cliquei aqui nesss bosta");
        });
    });
</script>
