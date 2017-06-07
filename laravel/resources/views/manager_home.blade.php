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

        <!-- Add competencies -->
        <div class="row">
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Cadastrar competências</div>

                    <div class="panel-body">
                        <form action="/user-competences" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-6 col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search_competence" class="form-control"
                                               placeholder="Buscar competência" id="search_competence"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-1">
                                        <button type="submit" class="btn btn-primary"> Adicionar competências</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped task-table" id="addCompetenceTable">
                                <!-- Table Headings -->
                                <!-- <td style="display:none;"> -->
                                <thead>
                                <th>Competência</th>
                                <th> Nivel</th>
                                <th>&nbsp;</th>
                                <th style="display:none;">id</th>

                                </thead>

                                <!-- Table Body -->
                                <tbody>

                                </tbody>
                            </table>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <!-- Show competencies -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Suas competências</div>

                    <div class="panel-body">
                        @if (count($competences) > 0)
                            <table class="table table-striped task-table" id="showCompetencesTable">
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
                                            <form action="/user-competency/{{ $competence->id }}" method="POST">
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
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Ações</div>

                    <div class="panel-body">
                        <div class="control-group">
                            <a href="{{ url('/tasks')}}" class="btn btn-primary">Cadastrar Tarefas</a>
                        </div>
                        <div class="control-group">
                            <a href="{{ url('/competences')}}" class="btn btn-primary">Cadastrar Competências</a>
                        </div>

                        <div class="control-group">
                            <a href="#" class="btn btn-primary">Cadastrar Equipe</a>
                        <!-- <a href="{{ url('/tasks')}}" class="btn btn-primary">Cadastrar Equipe</a> -->
                        </div>
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
            if (val == 2) {
                return "Intermediário";
            }
            if (val == 3) {
                return "Avançado";
            }
        }
        function toggleTable() {
            var lTable = document.getElementById("addCompetenceTable");
            lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
        }
        function getCurrentNumberOfRows(tableId) {
            return document.getElementById(tableId).getElementsByTagName("tr").length - 1;
        }
        function getRowCode(name, competenceId) {
            var code = '<tr>' +
                '<td class="table-text"> ' +
                    '<div class="competence_name">' +
                        name +
                    '</div>' +
                '   <input type="hidden" name="name[]" value="' + name + '" />' +
                '</td>' +
                '<td class="table-text">' +
                '<div class="competency_level">' +
                '<span class="competence_level_label" name="levels[]">Básico</span>'
                + '<input type="range" class="competence_level_slider" ' +
                'name="rangeInput" min="1" max="3" value ="1" onchange="updateTextInput(this);">' +
                '</div>' +
                '<input type="hidden" class="competence_level_class" name="competence_level[]" value="Básico" />' +
                '</td>' +
                '<td>' +
                '<button class="remove_unsaved_competence">x</button>' +
                '</td>' +
                '<td style="display:none;">' +
                '<div class="competence_id">' + competenceId +
                '</div>' +
                '<input type="hidden" name="competence_id[]" value="' + competenceId + '" />' +
                '</td>' +
                '</tr>';

            return code;
        }
        function addCompetence(name, competenceId) {
            var current_number_rows = getCurrentNumberOfRows("addCompetenceTable");
            if (current_number_rows == 0) {
                toggleTable();
            }
            //add new competenceToTable
            $("#addCompetenceTable").append(getRowCode(name, competenceId));
        }
        function removeCompetence() {
            var current_number_rows = getCurrentNumberOfRows("addCompetenceTable");
            if (current_number_rows == 0) {
                toggleTable();
            }
        }
        function updateTextInput(slider) {
            var rowHit = $(slider).parent().parent().parent();
            var sliderLabel = rowHit.find(".competence_level_label");
            var newLabel = getLabelForSliderValue(slider.value);
            sliderLabel.html(newLabel);
            var competenceLevelInputField = rowHit.find(".competence_level_class");
            competenceLevelInputField.val(newLabel);
            //$(".competence_level_class").val('ooola');
        }
        $(document).ready(function () {
            document.getElementById("addCompetenceTable").style.display = "none";
            $("#addCompetenceTable").on('click', '.remove_unsaved_competence', function () {
                $(this).parent().parent().remove();
                removeCompetence();
            });
            src = "{{ route('search') }}";
            $("#search_competence").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: src,
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function (data) {
                            response(data);

                        }
                    });
                },
                minLength: 2,
                select: function (e, ui) {
                    addCompetence(ui.item.value, ui.item.id);
                    $(this).val('');
                    return false;
                }

            });
        });
    </script>



@endsection
