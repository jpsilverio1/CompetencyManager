/**
 * Created by Jéssica on 2017-06-18.
 */
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
}
$(document).ready(function () {
    alert("carreguei");
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
