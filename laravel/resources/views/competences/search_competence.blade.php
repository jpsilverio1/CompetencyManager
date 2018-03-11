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

<div class="navbar-left" >
    <form class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar competência" name="q" id="search_competence">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </form>
</div>

<script>

    function retrieveCompetence(name, competenceID) {
        var path = '/competences/'+competenceID;
        window.location.href = path;
    }
    $(document).ready(function () {
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
            minLength: 1,
            select: function (e, ui) {
                retrieveCompetence(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>