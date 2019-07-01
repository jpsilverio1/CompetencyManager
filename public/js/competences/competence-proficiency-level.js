/**
 * Created by Jéssica on 2018-08-20.
 */
function getLabelForSliderValue(val) {
    return $('.competence-proficiency-level-labels').find('li:nth-child('+val+')').text();
}

function updateTextInput(slider, minProficiencyLevel) {
    var rowHit = $(slider).parent().parent().parent();
    var sliderLabel = rowHit.find(".competence_level_label");

    var translated_idx = parseInt(slider.value) + 1 - minProficiencyLevel;
    var newLabel = getLabelForSliderValue(translated_idx);
    sliderLabel.html(newLabel);
}

function getCurrentNumberOfRows(tableId) {
    return document.getElementById(tableId).getElementsByTagName("tr").length - 1;
}