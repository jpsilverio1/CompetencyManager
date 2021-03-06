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
            <input type="text" class="form-control" placeholder="Buscar cargos" name="q" id="search_jobrole">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </form>
</div>

<script>

    function retrieveJobRole(name, jobRoleID) {
        var path = '/jobroles/'+jobRoleID;
        window.location.href = path;
    }
    $(document).ready(function () {
        src_jobrole = "{{ route('search-jobrole') }}";
        $("#search_jobrole").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src_jobrole,
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
                retrieveJobRole(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>