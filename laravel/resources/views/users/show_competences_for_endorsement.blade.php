<div class="col-md-10">
    <div class="panel panel-default">
        <div class="panel-heading">Competências de {{$user->name}}</div>

        <div class="panel-body">
            @if (count($competences) > 0)
                <table class="table table-striped task-table" id="showCompetencesTable">
                    <!-- Table Headings -->
                    <thead>
                    <th>Competência</th>
                    <th>Nível</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th> <!--endorsement status -->
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                    @foreach ($competences as $competence)
                        <tr>
                            <!-- Task Name -->
                            <td class="table-text">
                                <div>{{ $competence->name }}</div>
                                <div> {{$user->getNumberOfEndorsementsForCompetence($user->endorsements(),$competence)}}</div>
                                {{$user->getEndorsement($competence, $profile_user)}}
                            </td>
                            <td class="table-text">
                                <div>{{ $competence->pivot->competency_level }}</div>
                                <div class="most-endorsed-level-percentage">23</div>
                            </td>

                            <td>
                                <div class="btn btn-info btn-circle">{{$user->getNumberOfEndorsementsForCompetence($user->endorsements(),$competence)}}</div>
                            </td>
                            <td>
                                <div>
                                    @if($user->loggedUserEndorsedCompetence($user->endorsements(),$competence) > 0)
                                        Você endossou essa competência
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                Você ainda não cadastrou nenhuma competência.
            @endif
        </div>
    </div>
</div>