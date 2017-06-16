@extends('layouts.app')
@section('content')


<div class="container">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Usuários</div>

            <div class="panel-body">
                @if (count($users) > 0)
                    <table class="table table-striped task-table" id="showCompetencesTable">


                        <!-- Table Body -->
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $user->level}}</div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    Não há usuários para exibição.
                @endif
            </div>
        </div>
    </div>
</div>

<div align="center">
    {{$users->render()}}
</div>
@endsection