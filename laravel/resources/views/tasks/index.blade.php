@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Tarefas</div>

                <div class="panel-body">
                    @if (count($tasks) > 0)
                        <table class="table table-striped task-table" id="showCompetencesTable">


                            <!-- Table Body -->
                            <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text">
                                        <div><a href="{{ route('tasks.show', $team->id) }}">{{ $task->name }}</a></div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        Não há tarefas para exibição.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div align="center">
        {{$tasks->render()}}
    </div>
@endsection