
// Simple list
Sortable.create(teamCandidates, { group: "taskTeam",
    sort: false});
Sortable.create(candidateTeam, { group: "taskTeam",
    filter: '.js-remove, .user-entered-member',
    onFilter: function (evt) {
        var item = evt.item,
            ctrl = evt.target;

        if (Sortable.utils.is(ctrl, ".js-remove")) {  // Click on remove button
            item.parentNode.removeChild(item); // remove sortable item
        }
    },
    onRemove: function (/**Event*/evt) {
        //var id = $( evt.item).attr('accessKey');
        fulfilledCompetencies = [];
        var listItems = $("#candidateTeam li");
        listItems.each(function(candidate) {
            var id = $(this).attr('accessKey');
            var source =  $(this).attr('data-member-source');
            if (source !== "userEnteredCandidate") {
                $.each(candidateContributions[id]["competenceRep"], function (i, elem) {
                    fulfilledCompetencies.push(String(elem));
                });
            }

        });
        updateCompetenciesFullfilment();
    },
    onAdd: function (/**Event*/evt) {
        //id do usuario
        var id = $( evt.item).attr('accessKey');
        var source = $( evt.item).attr('data-member-source');
        console.log("source: "+source);
        if (source !== "userEnteredCandidate") {
            $.each(candidateContributions[id]["competenceRep"], function (i, elem) {
                fulfilledCompetencies.push(String(elem));
            });
            updateCompetenciesFullfilment();
        }

    },
} );
function updateCompetenciesFullfilment() {
    $('.competence_status').each(function(i, obj) {
        var competenceId = $(this).attr('accesskey');
        if(jQuery.inArray(competenceId, fulfilledCompetencies) !== -1) {
            $(this).removeClass( "unfulfilled-competency" ).addClass( "fulfilled-competency" );
        } else {
            $(this).removeClass( "fulfilled-competency" ).addClass( "unfulfilled-competency" );
        }
    });
}

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        html:"true"
    });
    $('[data-toggle="popover"]').popover();
    $("#createTeamForm").submit( function(eventObj) {
        var listItems = $("#candidateTeam li");
        //getting the id's of the candidates inside the candidate team list and creating input fields with them
        listItems.each(function(candidate) {
            $('<input />').attr('type', 'hidden')
                .attr('name', "team_member_id[]")
                .attr('value', $(this).attr('accessKey'))
                .appendTo('#createTeamForm');
        });

        return true;
    });
});


