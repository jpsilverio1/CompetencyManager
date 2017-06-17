<table class="table table-striped task-table" id="showCompetencesTable">


    <!-- Table Body -->
    <tbody>
    @if (count($teams) > 0)
        @foreach ($teams as $team)
            <tr>
                <!-- Task Name -->
                <td class="table-text">
                    <div><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></div>
                </td>
            </tr>
        @endforeach
        <div align="center">
            {{$teams->render()}}
        </div>
    @else
        <tr>
            <td class="table-text">
                Não há equipes para exibição.
            </td>

        </tr>

    @endif
    </tbody>
</table>

