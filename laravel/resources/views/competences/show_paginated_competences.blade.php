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
                        @if($useCompetency)
                            {{\App\CompetenceProficiencyLevel::findOrFail($competence->pivot->competency_proficiency_level_id)->name}}
                        @else
                            {{\App\CompetenceProficiencyLevel::findOrFail($competence->pivot->competence_proficiency_level_id)->name}}
                        @endif
                    </td>
                @endif
                @if($showDeleteButton)
                    <td>

                        <form action="{{$path_to_removal}}{{ $competence->id }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
							<div id="removeCompetenceAlert"></div>
                            <button class="btn btn-link "><span id="removeButton" class="glyphicon glyphicon-remove text-muted"></span></button>
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
	
<script>
	function getCurrentNumberOfRows(tableId) {
        return document.getElementById(tableId).getElementsByTagName("tr").length - 1;
    }
	$(document).ready(function () {
		 $("#removeButton").on('click', function (e) {
			var current_number_rows = getCurrentNumberOfRows("showCompetencesTable");
			if (current_number_rows == 1) {
				e.preventDefault();
				$("#removeCompetenceAlert").html("Você precisa de pelo menos uma competência selecionada. Se deseja deletar a atual, primeiro adicione outra e então exclua esta.")
			} else {
				$("#removeCompetenceAlert").html("")
			}
        });
	});
</script>

