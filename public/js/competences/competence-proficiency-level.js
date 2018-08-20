/**
 * Created by Jéssica on 2018-08-20.
 */
function getLabelForSliderValue(val) {
    console.log("estou dentro do asset");
    //TODO: THIS WILL NOT WORK IF THE PROFICIENCY LEVEL IDS DON'T START AT 1. FIX IT!
    return $('.competence-proficiency-level-labels').find('li:nth-child('+val+')').text() + 'indiozinho';
}

function updateTextInput(slider) {
    var rowHit = $(slider).parent().parent().parent();
    var sliderLabel = rowHit.find(".competence_level_label");
    var newLabel = getLabelForSliderValue(slider.value);
    sliderLabel.html(newLabel);
}