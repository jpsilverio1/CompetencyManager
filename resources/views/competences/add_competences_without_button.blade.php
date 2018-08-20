<style>
	.ui-autocomplete {
		max-height: 300px;
		width: 100px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
	}
	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 100px;
	}
</style>

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
                    @if($showCompetenceLevel)
                        <th> Nivel</th>
                    @endif
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
    function toggleTable() {
        var lTable = document.getElementById("addCompetenceTable");
        lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
    }
    function getCurrentNumberOfRows(tableId) {
        return document.getElementById(tableId).getElementsByTagName("tr").length - 1;
    }
    function getCompetenceRowCode(name, competenceId) {
        var minProficiencyLevelId = {{$globalMinConpetenceProficiencyLevelId}};
        var maxProficiencyLevelId = {{$globalMaxConpetenceProficiencyLevelId}};
        var code;
        @if($showCompetenceLevel)
            code = '<tr>' +
            '<td class="table-text"> ' +
            '<div class="competence_name">' +
            name +
            '</div>' +
            '   <input type="hidden" name="competence_names[]" value="' + name + '" />' +
            '</td>' +
            '<td class="table-text">' +
            '<div class="competency_level">' +
            '<span class="competence_level_label" name="competence_levels[]">'+getLabelForSliderValue(minProficiencyLevelId)+'</span>'
            + '<input type="range" class="competence_level_slider" ' +
            'name="competency_proficiency_levels[]" min="'+minProficiencyLevelId+'" max="'+maxProficiencyLevelId+'" value ="'+minProficiencyLevelId+'" onchange="updateTextInput(this);">' +
            '</div>' +
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
        @else
            code = '<tr>' +
            '<td class="table-text"> ' +
            '<div class="competence_name">' +
            name +
            '</div>' +
            '   <input type="hidden" name="competence_names[]" value="' + name + '" />' +
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
        @endif


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

    function getCurrentCompetenceIdsInTable() {
        var lista = [];
        $('[name="competence_ids[]"]').each(function(){
            lista.push($(this).val());
        });
        return lista;
    }
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
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
                        term: request.term,
                        blacklistedIds:  getCurrentCompetenceIdsInTable()
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            minLength: 1,
            select: function (e, ui) {
                addCompetence(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>