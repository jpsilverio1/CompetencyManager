// Simple list
Sortable.create(teamCandidates, { group: "taskTeam" });
Sortable.create(candidateTeam, { group: "taskTeam" });



$(document).ready(function(){
    $("#createTeamForm").submit( function(eventObj) {
        var listItems = $("#candidateTeam li");
        listItems.each(function(candidate) {
            $('<input />').attr('type', 'hidden')
                .attr('name', "team_member_id[]")
                .attr('value', $(this).attr('accessKey'))
                .appendTo('#createTeamForm');
            console.log($(this).attr('id'));
        });

        return true;
    });
});


