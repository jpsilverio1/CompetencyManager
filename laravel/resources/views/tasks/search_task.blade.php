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
        console.log("olaaaaaaa "+src_task);
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