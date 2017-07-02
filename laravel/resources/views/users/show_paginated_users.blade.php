@if (count($users) > 0)
    <table class="table table-striped task-table" id="showCompetencesTable">
        @if($showDeleteButton)
            <th>Nome </th>
            <th> Função</th>
            <th>Excluir membro?</th>
        @endif
        <!-- Table Body -->
        <tbody>
        @foreach ($users as $user)
            <tr>
                <!-- Task Name -->
                <td class="table-text">
                    <div><a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a></div>
                </td>
                <td class="table-text text-capitalize">
                    <div>{{ $user->level}}</div>
                </td>
                @if($showDeleteButton)
                    <td>

                        <form action="{{$path_to_removal}}{{ $user->id }}" method="POST">
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
        {{$users->render()}}
    </div>
@else
    <table class="table" id="showCompetencesTable">
        <!-- Table Body -->
        <tbody>
            <tr>
                <td class="table-text">
                    Não há usuários para exibição.
                </td>

            </tr>
        </tbody>
    </table>

@endif