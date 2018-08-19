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

<div class="col-sm-3 col-md-3 navbar-right" >
    <form class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar usuário" name="q" id="search_user">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </form>
</div>

<script>

    function retrieveUser(name, userId) {
        let path = '/users/'+userId;
        window.location.href = path;
    }
    $(document).ready(function () {
        src_user = "{{ route('search-user') }}";
        console.log("usuario   "+src_user);
        $("#search_user").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src_user,
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
                retrieveUser(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>