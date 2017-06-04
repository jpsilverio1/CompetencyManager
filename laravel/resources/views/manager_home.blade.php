@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Equipes das quais você faz parte</div>

                <div class="panel-body">
                    @if (count($teams) > 0)
                        <table class="table table-striped task-table">
                            <!-- Table Headings -->
                            <thead>
                            <th>Equipe</th>
                            <th>&nbsp;</th>
                            </thead>

                            <!-- Table Body -->
                            <tbody>
                            @foreach ($teams as $team)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text">
                                        <div>{{ $team->name }}</div>
                                    </td>

                                    <td>
                                        <form action="/user-team/{{ $team->id }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button>x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        Você ainda não faz parte de nenhum time.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
