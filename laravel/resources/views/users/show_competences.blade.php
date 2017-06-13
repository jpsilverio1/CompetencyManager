    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Suas competências</div>

            <div class="panel-body">
                @if (count($competences) > 0)
                    <table class="table table-striped task-table" id="showCompetencesTable">
                        <!-- Table Headings -->
                        <thead>
                        <th>Competência</th>
                        <th>Nível</th>
                        <th>&nbsp;</th>
                        </thead>

                        <!-- Table Body -->
                        <tbody>
                        @foreach ($competences as $competence)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div>{{ $competence->name }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $competence->pivot->competency_level }}</div>
                                </td>

                                <td>
                                    <form action="/user-competency/{{ $competence->id }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button>x</button>
                                    </form>
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