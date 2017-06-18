<div class="col-md-6 form-group">
    <div class="panel panel-default">
        <div class="panel-heading">Cadastrar competências</div>

        <div class="panel-body">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-xs-6 col-md-6">
                        <div class="input-group stylish-input-group input-append">
                            <input type="text" name="search_competence" class="form-control"
                                   placeholder="Buscar competência" id="search_competence">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-search"></span>
                    </span>
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
    function getCompetenceRowCode(name, competenceId) {
        var code = '<tr>' +
            '<td class="table-text"> ' +
            '<div class="competence_name">' +
            name +
            '</div>' +
            '   <input type="hidden" name="competence_names[]" value="' + name + '" />' +
            '</td>' +
            '<td class="table-text">' +
            '<div class="competency_level">' +
            '<span class="competence_level_label" name="competence_levels[]">Básico</span>'
            + '<input type="range" class="competence_level_slider" ' +
            'name="rangeInput" min="1" max="3" value ="1" onchange="updateTextInput(this);">' +
            '</div>' +
            '<input type="hidden" class="competence_level_class" name="competence_levels[]" value="Básico" />' +
            '</td>' +
            '<td>' +
            '<button class="remove_unsaved_competence">x</button>' +
            '</td>' +
            '<td style="display:none;">' +
            '<div class="competence_id">' + competenceId +
            '</div>' +
            '<input type="hidden" name="competence_ids[]" value="' + competenceId + '" />' +
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
        $("#addCompetenceTable").append(getCompetenceRowCode(name, competenceId));
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
    }
    $(document).ready(function () {
        document.getElementById("addCompetenceTable").style.display = "none";
        $("#addCompetenceTable").on('click', '.remove_unsaved_competence', function () {
            $(this).parent().parent().remove();
            removeCompetence();
        });
        src_competence = "{{ route('search-competence') }}";
        $("#search_competence").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src_competence,
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