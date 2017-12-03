

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        html:"true"
    });
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


