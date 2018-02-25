
<div class="panel panel-default col-md-3 col-xs-1 col-md-offset-1">
    <div class="panel-heading" >
        Equipes sugeridas
    </div>
    <div class="panel-body">
                <?php $suitableAssigneesForTask = $task->getFinalRankAndExplanations()["novaRecomendacao"]; ?>
                @if (count($suitableAssigneesForTask) > 0)
                        <table class="table table-striped">
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
                                        fazer
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