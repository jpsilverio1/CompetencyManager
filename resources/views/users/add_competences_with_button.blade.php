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

<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Cadastrar competências</div>
        <div class="panel-body">
            <form action="/user-competences" method="POST">
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
                    <div class="form-group">
                        <div class=" col-sm-1">
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

<script>
    function toggleTable() {
        var lTable = document.getElementById("addCompetenceTable");
        lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
    }
    function getRowCode(name, competenceId) {
        var minProficiencyLevelId = {{$globalMinCompetenceProficiencyLevelId}};
        var maxProficiencyLevelId = {{$globalMaxCompetenceProficiencyLevelId}};
        var code = '<tr>' +
            '<td class="table-text"> ' +
            '<div class="competence_name">' +
            name +
            '</div>' +
            '   <input type="hidden" name="name[]" value="' + name + '" />' +
            '</td>' +
            '<td class="table-text">' +
            '<div class="competency_level">' +
            '<span class="competence_level_label" name="levels[]">'+getLabelForSliderValue(1)+'</span>'
            + '<input type="range" class="competence_level_slider" ' +
            'name="competence_proficiency_level[]" min="'+minProficiencyLevelId+'" max="'+maxProficiencyLevelId+'" value ="'+minProficiencyLevelId+'" onchange="updateTextInput(this, {{$globalMinCompetenceProficiencyLevelId}})">' +
            '</div>' +
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

    function getCurrentCompetenceIdsInTable() {
        var lista = [];
        $('[name="competence_id[]"]').each(function(){
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