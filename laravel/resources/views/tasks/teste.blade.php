@extends('layouts.app')
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>

</head>
@section('content')
    <!-- Latest Sortable -->
    <script src="http://rubaxa.github.io/Sortable/Sortable.js"></script>


    <!-- Simple List -->
    <ul id="teamCandidates" class="list-group col-md-2">
        @if (count($task->allCandidates()) > 0)
            @foreach ($task->allCandidates() as $candidate)
                <li class="list-group-item">{{$candidate->name}}</li>
            @endforeach
        @endif
    </ul>
    <div  class="col-md-2" style="display:block; -webkit-padding-start: 40px;" >
        <div class="layer title">List B</div>
        <ul id="candidateTeam" class="list-group col-md-4">








        </ul>
    </div>
    

    <script src="{{ asset('js/team-task-recomendation.js') }}"></script>
@endsection

