@extends('layouts.app')
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>

</head>
@section('content')
    <!-- Latest Sortable -->
    <script src="http://rubaxa.github.io/Sortable/Sortable.js"></script>


    <!-- Simple List -->
    <div style="text-align:center;" class="col-md-8 col-md-offset-2">
        <div class="layer title col-md-4 col-md-offset-1">List A</div>
        <div class="layer title col-md-4 col-md-offset-1">List B</div>
        <ul id="teamCandidates" class="list-group col-md-4 col-md-offset-1" style="background-color: white; min-height: 40px;max-width: 50%; padding:0;">
            @php ($taskCandidatesInfo = $task->allCandidates())
            @if (count($taskCandidatesInfo["candidates"]) > 0)
                @foreach ($taskCandidatesInfo["candidates"] as $key => $candidate)
                    <li class="list-group-item" accesskey="{{$candidate->id}}">{{$candidate->name}} -> {{count($taskCandidatesInfo["csndidatesContribution"][$key])}}</li>
                @endforeach
            @endif
        </ul>
        <ul id="candidateTeam" class="list-group col-md-4 col-md-offset-1" style="background-color: white; min-height: 40px;max-width: 50%; padding:0;">
        </ul>
    </div >


    <script src="{{ asset('js/team-task-recomendation.js') }}"></script>
    <!-- form start -->
    <form class="form-horizontal" id="createTeamForm"role="form" method="POST" action="{{ route('tasks.store-team') }}">
        {{ csrf_field() }}
        <input type="hidden" class="form-control" name="task_id" value="{{$task->id}}">
            <div class="form-group col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Cadastrar equipe</button>
            </div>
        </div>
        <!-- /.panel-body -->
    </form>
@endsection
