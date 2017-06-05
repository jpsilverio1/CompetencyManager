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
        function toggleTable() {
            console.log(getCurrentNumberOfRows("showCompetencesTable"));
            var lTable = document.getElementById("addCompetenceTable");
            lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
        }
        function getCurrentNumberOfRows(tableId) {
            return document.getElementById(tableId).getElementsByTagName("tr").length-1;
        }
        function getRowCode(name, competenceId) {
            var code = '<tr><td class="table-text"> <div class="competence_name" name="names[]">' + name +
                '</div></td><td class="table-text"><td class="table-text"> <div class="competency_level"><span class="competence_level_label" name="levels[]">Basico</span>'
            +'<input type="range" id="competence_level_slider" name="rangeInput" min="1" max="3" value ="1" onchange="updateTextInput(this.value);"></div></td>'
            +'<td><button class="remove_unsaved_competence">x</button></td>'+
            '<td><div class="competence_id" name="competence_id[]">'+competenceId+'</div></td></tr>';

            return code;
        }
        function addCompetence(name, competenceId) {
            console.log($("#search_competence").value);
            var current_number_rows = getCurrentNumberOfRows("addCompetenceTable");
            console.log(current_number_rows);
            if (current_number_rows == 0) {
                toggleTable();
            }
            //add new competenceToTable
            //$("#addCompetenceTable").append('<tr valign="top"><th scope="row"><label for="customFieldName">Custom Field</label></th><td><input type="text" class="code" id="customFieldName" name="customFieldName[]" value="" placeholder="Input Name" /> &nbsp; <input type="text" class="code" id="customFieldValue" name="customFieldValue[]" value="" placeholder="Input Value" /> &nbsp; <a href="javascript:void(0);" class="remCF">Remove</a></td></tr>');
            $("#addCompetenceTable").append(getRowCode(name, competenceId));
        }
        function removeCompetence() {
            var current_number_rows = getCurrentNumberOfRows("addCompetenceTable");
            if (current_number_rows == 0) {
                toggleTable();
            }
        }
        function updateTextInput(val) {
            document.getElementById('competence_level_label').innerHTML = getLabelForSliderValue(val);
        }
        $(document).ready(function() {
            //$('#competence_level_label').text(getLabelForSliderValue($('#competence_level_slider').val()));
            document.getElementById("addCompetenceTable").style.display="none";
            ;
            $("#addCompetenceTable").on('click','.remove_unsaved_competence',function(){
                $(this).parent().parent().remove();
                removeCompetence();
            });

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
                            response(data);

                        }
                    });
                },
                minLength: 2,
                select:function(e,ui){
                    addCompetence(ui.item.value, ui.item.id);
                    $(this).val('');
                    return false;
                }

            });
        });
    </script>



@endsection