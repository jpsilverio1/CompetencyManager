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
            <input type="text" class="form-control" placeholder="Buscar tarefa" name="q" id="search_task">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
    </form>
</div>

<script>

    function retrieveTask(name, taskID) {
        var path = '/tasks/'+taskID;
        window.location.href = path;
    }
    $(document).ready(function () {
        src_task = "{{ route('search-task') }}";
        $("#search_task").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src_task,
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
                retrieveTask(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>