<table class="table table-striped learningaid-table" id="showCompetenceTable">
    <!-- Table Body -->
    <tbody>
    @if (count($learningaids) > 0)
        @foreach ($learningaids as $learningaid)
            <tr>
                <!-- Task Name -->
                <td class="table-text">
                    <div><a href="{{ route('learningaids.show', $learningaid->id) }}">{{ $learningaid->name }}</a></div>
                </td>

                <td><a href="{{ route('learningaids.edit', $learningaid->id) }}"/><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>

                <form class="col-xs-offset-1" id="deleteLearningAidForm" role="form" method="POST" action="{{ route('learningaids.destroy', $learningaid->id ) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE" />
                    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
                </form>
            </tr>
        @endforeach
    </tbody>
</table>
<div align="center">
    {{$learningaids->render()}}
</div>
@else
    <tr>
        <td class="table-text">
            {{$noLearningAidsMessage}}
        </td>

    </tr>
    </tbody>
    </table>
@endif




{{--<table class="table table-striped task-table" id="showCompetencesTable">
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
--}}