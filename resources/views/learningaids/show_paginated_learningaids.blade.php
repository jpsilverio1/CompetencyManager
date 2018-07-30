<table class="table table-striped learningaid-table" id="showCompetenceTable">
    <!-- Table Body -->
    <tbody>
    @if (count($learningAids) > 0)
        @foreach ($learningAids as $learningAid)
            <tr>
                <!-- LearningAid Name -->
                <td class="table-text">
                    <div><a href="{{ route('learningaids.show', $learningAid->id) }}">{{ $learningAid->name }}</a></div>
                </td>
                @if (Auth::user()->isManager())
                <td><a href="{{ route('learningaids.edit', $learningAid->id) }}"/><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="glyphicon glyphicon-pencil"></span></button></p></td>

                <form class="col-xs-offset-1" id="deleteLearningAidForm" role="form" method="POST" action="{{ route('learningaids.destroy', $learningAid->id ) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE" />
                    <td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" ><span class="glyphicon glyphicon-trash"></span></button></p></td>
                </form>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<div align="center">
    {{$learningAids->render()}}
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
