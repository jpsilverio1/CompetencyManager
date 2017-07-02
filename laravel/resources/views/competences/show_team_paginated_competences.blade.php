<table class="table table-striped task-table" id="showCompetencesTable">


    <!-- Table Body -->
    <tbody>
    @if (count($competences) > 0)
        @if ($showCompetenceLevel)
            <thead>
            <th>Competência</th>

                <th>Nível</th>
            @if($showDeleteButton)
                <th>Excluir?</th>
            @endif
            </thead>
        @endif
        @foreach ($competences as $competence)
            <tr>
                <!-- Task Name -->
                <td class="table-text">
                    <div><a href="{{ route('competences.show', $competence->id) }}">{{ $competence->name }}</a></div>
                </td>
                @if ($showCompetenceLevel)
                    <td class="table-text text-capitalize">
                        {{$competence->pivot->competency_level}}
                    </td>
                @endif
                @if($showDeleteButton)
                    <td>
                        <form action="/team-competency/{{ $competence->id }}/{{$team->id}}" method="POST">                            {{ method_field('DELETE') }}

                            <button>x</button>
                        </form>
                    </td>
                @endif
            </tr>


        @endforeach

    </tbody>
</table>
<div align="center">
    {{$competences->render()}}
</div>
    @else
        <tr>
            <td class="table-text">
                {{$noCompetencesMessage}}
            </td>

        </tr>
    </tbody>
</table>
    @endif
