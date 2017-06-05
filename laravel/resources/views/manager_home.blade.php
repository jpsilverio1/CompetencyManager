@extends('layouts.app')
@section('content')


    <div class="container">
    <div class="row">
        <div class="col-md-6">
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
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Tarefas</div>

                <div class="panel-body">
                    Em construção!
                </div>
            </div>
        </div>
    </div>

        <!-- Competencias -->
        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Cadastrar competências</div>

                    <div class="panel-body">
                        <form action="/hms/accommodations" method="GET">
                            <div class="row">
                                <div class="col-xs-6 col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search_competence" class="form-control" placeholder="Buscar competência" id="search_competence"/>
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-striped task-table">
                            <!-- Table Headings -->
                            <thead>
                            <th>Competência</th>
                            <th> Nivel</th>
                            <th>&nbsp;</th>

                            </thead>

                            <!-- Table Body -->
                            <tbody>
                            <tr>
                                <!-- Task Name -->
                                <td class="table-text">
                                    <div id="competence_name">Uma competencia</div>
                                </td>
                                <td class="table-text">
                                    <div>
                                        <span id="competence_level_label"></span>
                                        <input type="range" id="competence_level_slider" name="rangeInput" min="1" max="3" value ="1" onchange="updateTextInput(this.value);">
                                    </div>
                                </td>
                                <td>
                                    <button id="remove_unsaved_competence">x</button>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Suas competências</div>

                    <div class="panel-body">
                        @if (count($competences) > 0)
                            <table class="table table-striped task-table">
                                <!-- Table Headings -->
                                <thead>
                                <th>Competência</th>
                                <th>Nível</th>
                                <th>&nbsp;</th>
                                </thead>

                                <!-- Table Body -->
                                <tbody>
                                @foreach ($competences as $competence)
                                    <tr>
                                        <!-- Task Name -->
                                        <td class="table-text">
                                            <div>{{ $competence->name }}</div>
                                        </td>
                                        <td class="table-text">
                                            <div>{{ $competence->pivot->competency_level }}</div>
                                        </td>

                                        <td>
                                            <form action="/user-team/{{ $competence->id }}" method="POST">
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
                            Você ainda não cadastrou nenhuma competência.
                        @endif
                    </div>
                </div>
            </div>
        </div>

</div>


    <script>
        function getLabelForSliderValue(val) {
            if (val == 1) {
                return "Básico";
            }
            if (val ==2) {
                return "Intermediário";
            }
            if (val == 3) {
                return "Avançado";
            }
        }
        function updateTextInput(val) {
            document.getElementById('competence_level_label').innerHTML = getLabelForSliderValue(val);
        }
        $(document).ready(function() {
            $('#competence_level_label').text(getLabelForSliderValue($('#competence_level_slider').val()));
            src = "{{ route('search') }}";
            $("#search_competence").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: src,
                        dataType: "json",
                        data: {
                            term : request.term
                        },
                        success: function(data) {
                            console.log(data);
                            response(data);

                        }
                    });
                },
                minLength: 2,
                select:function(e,ui){
                    console.log(ui);
                    alert(ui.item.id);
                    //alert(ui.item.value);
                }

            });
        });
    </script>



@endsection
