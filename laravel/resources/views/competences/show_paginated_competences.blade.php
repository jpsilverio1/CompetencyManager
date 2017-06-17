<table class="table table-striped task-table" id="showCompetencesTable">


    <!-- Table Body -->
    <tbody>
    @if (count($competences) > 0)
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
            </tr>


        @endforeach
        <div align="center">
            {{$competences->render()}}
        </div>
    @else
        <tr>
            <td class="table-text">
                Não há competências para exibição.
            </td>

        </tr>

    @endif
    </tbody>
</table>