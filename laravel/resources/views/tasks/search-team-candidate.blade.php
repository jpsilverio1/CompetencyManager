<div class="panel panel-default col-md-3 col-xs-1 col-md-offset-1">
    <div class="panel-heading" >
        Adicionar usuário especifico
    </div>
    <form class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar usuário" name="q" id="search-team-candidate">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </div>
        </div>
    </form>
</div>

<script>

    function retrieveTeamCandidate(name, userId) {
        $("#candidateTeam").append('<li class="list-group-item user-entered-member" data-member-source="userEnteredCandidate" accesskey="'+userId+'">'
            +name +
            '  <span class="glyphicon glyphicon-info-sign" data-toggle = "tooltip" data-placement = "top" title="usuário adicionado manualmente"/></span>' +
            ' <a  href="#"><i class="js-remove">✖</i></a>'+
            '</li>');
        $('[data-toggle="tooltip"]').tooltip({
            html:"true"
        });
    }
    function getCurrentUserIdsInLists() {
        var lista = [];
        $('.list-group-item').each(function(){
            lista.push($(this).attr('accesskey'));
        });
        return lista;
    }
    $(document).ready(function () {
        src_user = "{{ route('search-team-candidate') }}";
        $("#search-team-candidate").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src_user,
                    dataType: "json",
                    data: {
                        term: request.term,
                        blacklistedIds:  getCurrentUserIdsInLists()
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            minLength: 1,
            select: function (e, ui) {
                retrieveTeamCandidate(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>