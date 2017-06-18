<div class="col-md-6 form-group">
    <div class="panel panel-default">
        <div class="panel-heading">Adicionar membros</div>

        <div class="panel-body">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <div class="input-group stylish-input-group input-append">
                        <input type="text" name="search_user_add" class="form-control"
                               placeholder="Buscar usuário" id="search_user_add">
                        <span class="input-group-addon">
                                <span class="glyphicon glyphicon-search"></span>
                    </span>
                    </div>
                </div>
            </div>
            <table class="table table-striped task-table" id="addUserTable">
                <!-- Table Headings -->
                <!-- <td style="display:none;"> -->
                <thead>
                <th>Usuário</th>
                <th>&nbsp;</th>
                <th style="display:none;">id</th>

                </thead>

                <!-- Table Body -->
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    function toggleUserTable() {
        var lTable = document.getElementById("addUserTable");
        lTable.style.display = (lTable.style.display == "table") ? "none" : "table";
    }
    function getCurrentNumberOfUserRows(tableId) {
        return document.getElementById(tableId).getElementsByTagName("tr").length - 1;
    }
    function getUserRowCode(name, userId) {
        var code = '<tr>' +
            '<td class="table-text"> ' +
            '<div class="user_name">' +
            name +
            '</div>' +
            '   <input type="hidden" name="user_names[]" value="' + name + '" />' +
            '</td>' +
            '<td>' +
            '<button class="remove_unsaved_user">x</button>' +
            '</td>' +
            '<td style="display:none;">' +
            '<div class="competence_id">' + userId +
            '</div>' +
            '<input type="hidden" name="user_ids[]" value="' + userId + '" />' +
            '</td>' +
            '</tr>';

        return code;
    }
    function addUser(name, userId) {
        var current_number_rows = getCurrentNumberOfUserRows("addUserTable");
        if (current_number_rows == 0) {
            toggleUserTable();
        }
        //add new competenceToTable
        $("#addUserTable").append(getUserRowCode(name, userId));
    }
    function removeUser() {
        var current_number_rows = getCurrentNumberOfUserRows("addUserTable");
        if (current_number_rows == 0) {
            toggleUserTable();
        }
    }

    $(document).ready(function () {
        document.getElementById("addUserTable").style.display = "none";
        $("#addUserTable").on('click', '.remove_unsaved_user', function () {
            $(this).parent().parent().remove();
            removeUser();
        });

        src_user = "{{ route('search-user') }}";
        $("#search_user_add").autocomplete({

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
                addUser(ui.item.value, ui.item.id);
                $(this).val('');
                return false;
            }

        });
    });
</script>