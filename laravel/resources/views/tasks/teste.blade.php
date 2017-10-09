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
            @if (count($task->allCandidates()) > 0)
                @foreach ($task->allCandidates() as $candidate)
                    <li class="list-group-item">{{$candidate->name}}</li>
                @endforeach
            @endif
        </ul>
        <ul id="candidateTeam" class="list-group col-md-4 col-md-offset-1" style="background-color: white; min-height: 40px;max-width: 50%; padding:0;">
        </ul>
    </div >


    <script src="{{ asset('js/team-task-recomendation.js') }}"></script>
@endsection
