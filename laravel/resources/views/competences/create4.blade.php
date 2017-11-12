@extends('layouts.app')
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-fullScreen">
                <div class="panel-heading">Criar novas competências</div>
                <div class="panel-header with-border">
                    {{var_dump(session()->getOldInput())}}
                </div>
                <!-- /.panel-header -->
                <!-- form start -->
                <form class="form-horizontal" id="addCompetencesForm"role="form" method="POST" action="{{ route('competences.store') }}">
                    {{ csrf_field() }}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            Houve algum problema ao adicionar as competências.<br />
                        </div>
                    @endif
                    <div class="panel-body">

                        <div class="panel-group" id="accordion" role="tablist"
                             aria-multiselectable="true">
                            <!-- Inicio -->
                            <div class="col-sm-12" style="margin-bottom: 0;">
                                <div class="panel panel-default" id="panel1">
                                    <div class="panel-heading" role="tab" id="heading1">
                                        <h4 class="panel-title">
                                            <a class="" id="panel-lebel1" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1"
                                               aria-expanded="true" aria-controls="collapse1"> Competencia
                                            </a>
                                            <div class="actions_div" style="position: relative; top: -26px;">
                                                <a href="#" accesskey="1" class="pull-right" id="addChildCompetence">
					 	<span class="glyphicon glyphicon-plus">
					 	</span>Adicionar sub competência
                                                </a>
                                            </div>
                                        </h4>
                                    </div>
                                    <div id="collapse1" class="panel-collapse collapse in"role="tabpanel" aria-labelledby="heading1">
                                        <div class="panel-body">
                                            <table class="table table-striped task-table" id="addCompetencesTable">
                                                <tbody>
                                                <tr>
                                                    <td class="form-group col-md-5{{ $errors->has('name.0') ? ' has-error' : '' }}">
                                                        <div class="col-md-offset-1">
                                                            <input type="text" class="form-control" name="name[]" placeholder="Nome da competência"  value="{{ old("name.0") }}">
                                                            @if ($errors->has('name.0'))
                                                                <span class="help-block">
                                        <strong>{{ $errors->first('name.0') }}</strong>
                                    </span>
                                                            @endif
                                                        </div>

                                                    </td>
                                                    <td class="form-group col-md-5 col-md-offset-2{{ $errors->has('description.0') ? ' has-error' : '' }}">
                                                        <div>
                                                            <input type="text" class="form-control" name="description[]" placeholder="Descrição da competência" value="{{ old("description.0") }}">
                                                            @if ($errors->has('description.0'))
                                                                <span class="help-block">
                                        <strong>{{ $errors->first('description.0') }}</strong>
                                    </span>
                                                            @endif
                                                            <input type="hidden" class="form-control" name="competence_ui_id[]" value="{{ old("competence_ui_id.0", 1) }}">
                                                            <input type="hidden" class="form-control" name="parent_ui_id[]" min="-1" value="{{ old("parent_ui_id.0", -1) }}">
                                                            <input type="hidden" class="form-control" name="isNewCompetence[]" value="{{ old("isNewCompetence.0", 'true') }}">

                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- final -->
                            @php ($nameIdx = 1)
                            @php ($dbIdIdx = 0)
                            @for ($i=1; $i<sizeOf(old('competence_ui_id')); $i++)
                                @php ($parentId = old("parent_ui_id.$i"))
                                @php ($competenceUiId = old("competence_ui_id.$i"))
                                @if (old("isNewCompetence.$i") === "true")
                                    @php ($competenceName = old("name.$nameIdx"))
                                    @php ($competenceDescription = old("description.$nameIdx"))
                                    @php ($descriptionError = $errors->has("description.$nameIdx") ? $errors->first("description.$nameIdx") : '')
                                    @php ($nameError = $errors->has("name.$nameIdx") ? $errors->first("name.$nameIdx") : '')
                                    @if ($parentId == -1)
                                        <script type="text/javascript">
                                            console.log("nova root"+{{$parentId}});
                                            createNewRootCompetence({{$competenceUiId}}, true, 'in', '', '{{$competenceName}}', '{{$competenceDescription}}', '{{$nameError}}', '{{$descriptionError}}');
                                        </script>
                                    @else
                                        <script type="text/javascript">
                                            console.log("nova sub"+{{$parentId}});
                                            addNewSubCompetence(true, {{$competenceUiId}}, {{$parentId}}, '{{$competenceName}}', '{{$competenceDescription}}', '{{$nameError}}', '{{$descriptionError}}');
                                        </script>
                                    @endif
                                    @php ($nameIdx++)
                                @else
                                    <script type="text/javascript">
                                        console.log("database");
                                        @php ($competenceUiId =  old("competence_db_id.$dbIdIdx"))
                                        tryThisThing({{$competenceUiId}}, {{$competenceUiId}}, {{$parentId}});
                                    </script>
                                    @php ($dbIdIdx++)
                                @endif
                            @endfor
                        </div>

                        <div class="col-md-12 text-center" style="margin-top:15px;">
                            <button class="btn btn-success" id="addRootCompetenceButton" value=""><span class="glyphicon glyphicon-plus"></span> Adicionar nova competência</button>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-5 col-xs-offset-1">
                                <button type="submit" class="btn btn-primary">Cadastrar Competências</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </form>
            </div>
        </div>
    </div>
@endsection
<script type="text/javascript">
    function tryThisThing(competenceId, uiCompetenceId, parentId) {
        console.log("tentando");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        src_competence_url = "{{ route('search-competence-db') }}";
        $.ajax({
            type: 'POST',
            url: src_competence_url,
            data: {
                term: competenceId,
            },
            dataType: 'json',
            success: function (data) {
                console.log("olha essa data"+data.name+ " "+data.description);
                addNewSubCompetenceFromDatabase(true, uiCompetenceId, parentId, competenceId, data.name, data.description);

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
    function createNewRootCompetence(uiCompetenceId, ariaExpanded, expandedClass, collapsedClass, competenceName, competenceDescription, nameError, descriptionError) {
        var wrapper = $("#accordion");
        console.log("input "+uiCompetenceId+" "+competenceName);
        var competenceDescStr = '>';
        if (competenceDescription != null) {
            var competenceDescStr = 'value="'+competenceDescription+'">';
        }
        var competenceNameStr = '>';
        if (competenceName != null) {
            var competenceNameStr = 'value="'+competenceName+'">';
        }
        var nameErrorStr = '';
        var descriptionErrorStr = '';
        var nameErrorStatus = '';
        var descriptionErrorStatus = '';
        if (nameError != null && nameError != '') {
            nameErrorStatus = ' has-error'
            nameErrorStr = '<span class="help-block"> ' +
                ' <strong>'+nameError+'</strong>  </span>';

        }
        if (descriptionError != null && descriptionError != '') {
            descriptionErrorStatus = ' has-error'
            descriptionErrorStr = '<span class="help-block"> ' +
                ' <strong>'+descriptionError+'</strong>  </span>';

        }
        $(wrapper).append('<div class="col-sm-12" style="margin-bottom: 0;">' +
            '<div class="panel panel-default"  id="panel'+ uiCompetenceId +'">' +
            '<div class="panel-heading" role="tab" id="heading'+ uiCompetenceId +'">' +
            '<h4 class="panel-title">' +
            '<a class="" id="panel-lebel'+ uiCompetenceId +'"  role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapse'+ uiCompetenceId +'" ' +
            'aria-expanded="'+ariaExpanded+'" aria-controls="collapse'+ uiCompetenceId +'"> Competencia '+uiCompetenceId +
            '</a>' +
            '<div class="actions_div" style="position: relative; top: -26px;">' +
            '<a href="#" accesskey="'+ uiCompetenceId +'" class="remove_ctg_panel exit-btn pull-right">' +
            '<span class="glyphicon glyphicon-remove">' +
            '</span>' +
            '</a>' +
            '<a href="#" accesskey="'+ uiCompetenceId +'"  class="pull-right" id="addChildCompetence">' +
            '<span class="glyphicon glyphicon-plus">' +
            '</span>Adicionar subcompetência' +
            '</a>' +
            '</div>' +
            '</h4>' +
            '</div>' +
            '<div id="collapse'+ uiCompetenceId +'" class="panel-collapse collapse '+expandedClass+'"role="tabpanel" aria-labelledby="heading'+ uiCompetenceId +'">'+
            '<div class="panel-body">' +
            '<table class="table table-striped task-table" class="addCompetencesTable">' +
            '<tbody>' +
            '<tr>' +
            '<td class="form-group col-md-5'+nameErrorStatus+'">' +
            '<div class="col-md-offset-1">' +
            '<input type="text" class="form-control" name="name[]" placeholder="Nome da competência"'+competenceNameStr +
                nameErrorStr +
            '</div>' +
            '</td>' +
            '<td class="form-group col-md-5 col-md-offset-2 '+descriptionErrorStatus+'">' +
            '<div>' +
            '<input type="text" class="form-control" name="description[]" placeholder="Descrição da competência"'+competenceDescStr +
                descriptionErrorStr +
            '<input type="hidden" class="form-control" name="competence_ui_id[]" value="'+uiCompetenceId+'">' +
            '<input type="hidden" class="form-control" name="parent_ui_id[]" min="-1" value="-1">' +
            '<input type="hidden" class="form-control" name="isNewCompetence[]" value="true">' +
            '</div>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');
    }
    function addNewSubCompetence(addNewCompetencePanel, uiCompetenceId, parentId, competenceName, competenceDescription, nameError, descriptionError) {
        var wrapper = $("#accordion");
        if (addNewCompetencePanel) {
            console.log("criar painel");
            createParentPanel(uiCompetenceId, parentId, wrapper);
        } else {
            console.log("nao criar painel");
        }
        var competenceDescStr = '>';
        if (competenceDescription != null) {
            var competenceDescStr = ' value="'+competenceDescription+'">';
        }
        var competenceNameStr = '>';
        if (competenceName != null) {
            var competenceNameStr = ' value="'+competenceName+'">';
        }
        var nameErrorStr = '';
        var descriptionErrorStr = '';
        var nameErrorStatus = '';
        var descriptionErrorStatus = '';
        if (nameError != null && nameError != '') {
            nameErrorStatus = ' has-error'
            nameErrorStr = '<span class="help-block"> ' +
                ' <strong>'+nameError+'</strong>  </span>';

        }
        if (descriptionError != null && descriptionError != '') {
            descriptionErrorStatus = ' has-error'
            descriptionErrorStr = '<span class="help-block"> ' +
                ' <strong>'+descriptionError+'</strong>  </span>';

        }
        $('#panel'+uiCompetenceId).find('#TextBoxDiv'+uiCompetenceId).append(
            '<table class="table table-striped task-table" class="addCompetencesTable">' +
            '<tbody>' +
            '<tr>' +
            '<td class="form-group col-md-5 '+nameErrorStatus+'">' +
            '<div class="col-md-offset-1">' +
            '<input type="text" class="form-control" name="name[]" placeholder="Nome da competência"'+competenceNameStr +
            nameErrorStr +
            '</div>' +
            '</td>' +
            '<td class="form-group col-md-5 col-md-offset-2 '+descriptionErrorStatus+'">' +
            '<div>' +
            '<input type="text" class="form-control" name="description[]" placeholder="Descrição da competência"'+competenceDescStr +
            descriptionErrorStr +
            '<input type="hidden" class="form-control" name="competence_ui_id[]" value="'+uiCompetenceId+'">' +
            '<input type="hidden" class="form-control" name="parent_ui_id[]" min="-1" value="'+parentId+'">' +
            '<input type="hidden" class="form-control" name="isNewCompetence[]" value="true">' +
            '</div>' +
            '</td>' +
            '<td>' +
            '<a href="#" accesskey="'+ uiCompetenceId +'" data-parentid="'+parentId+'" class="remove_sub_competence_individual_fields exit-btn pull-right">' +
            '<span class="glyphicon glyphicon-remove btn-danger">' +
            '</span>' +
            '</a>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>'
        );
    }
    function createParentPanel(uiCompetenceId, parentId, wrapper) {
        var parentPanel = '#panel'+ parentId;
        var ariaExpanded = true;
        var expandedClass = 'in';
        var collapsedClass = 'collapsed';
        var catgName = "Subcompetência - "+uiCompetenceId;
        $(wrapper).find(parentPanel).append('<div class="col-sm-12" style="margin-bottom: 0;">' +
            '<div class="panel panel-default" id="panel'+uiCompetenceId+'">' +
            '<div class="panel-heading" role="tab" id="heading'+uiCompetenceId+'">' +
            '<h4 class="panel-title">' +
            '<a  id="panel-lebel'+ uiCompetenceId +'" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+ uiCompetenceId+'" ' +
            'aria-expanded="'+ariaExpanded+'" aria-controls="collapse'+ uiCompetenceId+'"> '+catgName+' </a>' +
            '<div class="actions_div" style="position: relative; top: -26px;">' +
            '<a href="#" accesskey="'+uiCompetenceId +'" class="remove_ctg_panel exit-btn pull-right"><span class="glyphicon glyphicon-remove"></span></a>' +
            '<a href="#" accesskey="'+ uiCompetenceId +'" class="pull-right" id="addChildCompetence"> <span class="glyphicon glyphicon-plus"></span> Adicionar subcompetência</a>' +
            '</h4>' +
            '</div>' +
            '<div id="collapse'+ uiCompetenceId+'" class="panel-collapse collapse '+expandedClass+'"role="tabpanel" aria-labelledby="heading'+uiCompetenceId+'">'+
            '<div class="panel-body">' +
            '<div id="TextBoxDiv'+ uiCompetenceId +'"></div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
    }
    function addNewSubCompetenceFromDatabase(addNewCompetencePanel, uiCompetenceId, parentId, dbId, competenceName, competenceDescription) {
        var wrapper = $("#accordion");
        if (addNewCompetencePanel) {
            console.log("criar painel");
            createParentPanel(uiCompetenceId, parentId, wrapper);
        } else {
            console.log("nao criar painel");
        }
        $('#panel'+uiCompetenceId).find('#TextBoxDiv'+uiCompetenceId).append(
            '<table class="table table-striped task-table" class="addCompetencesTable">' +
            '<tbody>' +
            '<tr>' +
            '<td class="form-group col-md-5">' +
            '<div class="col-md-offset-1">' +
            '<input type="text" value="'+competenceName+'" class="form-control" name="name[]" disabled="true"">' +
            '</div>' +
            '</td>' +
            '<td class="form-group col-md-5 col-md-offset-2">' +
            '<div>' +
            '<input type="text" value="'+competenceDescription+'" class="form-control" name="description[]" disabled="true"">' +
            '<input type="hidden" class="form-control" name="competence_ui_id[]" value="'+uiCompetenceId+'">' +
            '<input type="hidden" class="form-control" name="competence_db_id[]" value="'+dbId+'">' +
            '<input type="hidden" class="form-control" name="parent_ui_id[]" min="-1" value="'+parentId+'">' +
            '<input type="hidden" class="form-control" name="isNewCompetence[]" value="false">' +
            '</div>' +
            '</td>' +
            '<td>' +
            '<a href="#" accesskey="'+ uiCompetenceId +'" data-parentid="'+parentId+'" class="remove_sub_competence_individual_fields exit-btn pull-right">' +
            '<span class="glyphicon glyphicon-remove btn-danger">' +
            '</span>' +
            '</a>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>'
        );
    }
    $(document).ready(function(){

        $('.btn-primary').click(function(){
            $('#competenceTemplate').remove();

        });


        $('body').on('click','.removeButton',function(){
            var $row  = $(this).parents('.start-form-row');
            // Remove element containing the fields
            $row.remove();
            competencyIndex--;
        });


    });
    $(document).ready(function(){
        var counter = 2;
        var wrapper = $("#accordion");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        src_competence = "{{ route('search-competence') }}";
        $("#search_competence").on('autocomplete', function(e) {
            console.log("ola mundo feliz333");
        });



        $("#addRootCompetenceButton").on("click", function(e){
            e.preventDefault();
            var catgName = 'Competência - '+counter;
            if(counter<11){
                var ariaExpanded = true;
                var expandedClass = 'in';
                var collapsedClass = '';
                createNewRootCompetence(counter,ariaExpanded, expandedClass, collapsedClass);
                counter++;
            } else {
                alert("Não é possível criar mais de 10 competências por vez");
            }

        });

        var x = 1;
        $(wrapper).on("click","#addChildCompetence", function(e){
            e.preventDefault();
            var parentId = $(this).attr('accesskey');
            var parentPanel = '#panel'+ parentId;
            var ariaExpanded = true;
            var expandedClass = 'in';
            var collapsedClass = 'collapsed';
            var catgName = "Subcompetência - "+counter;
            $(wrapper).find(parentPanel).append('<div class="col-sm-12" style="margin-bottom: 0;">' +
                '<div class="panel panel-default" id="panel'+counter+'">' +
                    '<div class="panel-heading" role="tab" id="heading'+counter+'">' +
                        '<h4 class="panel-title">' +
                        '<a  id="panel-lebel'+ counter +'" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+ counter+'" ' +
                    'aria-expanded="'+ariaExpanded+'" aria-controls="collapse'+ counter+'"> '+catgName+' </a>' +
                        '<div class="actions_div" style="position: relative; top: -26px;">' +
                        '<a href="#" accesskey="'+counter +'" class="remove_ctg_panel exit-btn pull-right"><span class="glyphicon glyphicon-remove"></span></a>' +
                        '<a href="#" accesskey="'+ counter +'" class="pull-right" id="addChildCompetence"> <span class="glyphicon glyphicon-plus"></span> Adicionar subcompetência</a>' +
                        '</h4>' +
                    '</div>' +
                    '<div id="collapse'+ counter+'" class="panel-collapse collapse '+expandedClass+'"role="tabpanel" aria-labelledby="heading'+counter+'">'+
                        '<div class="panel-body">' +
                            '<div id="TextBoxDiv'+ counter +'"></div>' +
                            '<div class="childCategoryOptionButtons">' +
                                '<div class="col-sm-2 ">' +
                                    '<a class="btn btn-xs btn-primary" accesskey="'+ counter +'" data-parentid="'+parentId+'" id="addNewSubCompetence" ><span class="glyphicon glyphicon-plus"></span> Adicionar nova subcompetência</a>' +
                                '</div>' +
                                '<div class="col-sm-offset-1 col-md-2">' +
                                    '<a class="btn btn-xs btn-primary" accesskey="'+ counter +'" data-parentid="'+parentId+'" id="addSubCompetenceFromDatabase" ><span class="glyphicon glyphicon-plus"></span> Adicionar subcompetência já cadastrada</a>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>'
            );
            x++;
            counter++;


        });

        var y = 1;
        $(wrapper).on("click","#addNewSubCompetence", function(e){
            e.preventDefault();
            var accesskey = $(this).attr('accesskey');
            var parentId = $(this).attr('data-parentid');
            console.log("add new competence"+accesskey+"parent= "+parentId);
            y++;
            $(this).closest('.childCategoryOptionButtons').remove();
            addNewSubCompetence(false, accesskey, parentId);

        });
        $(wrapper).on("click","#addSubCompetenceFromDatabase", function(e){
            e.preventDefault();
            var accesskey = $(this).attr('accesskey');
            var parentId = $(this).attr('data-parentid');
            y++;
            $(this).closest('.childCategoryOptionButtons').remove();
            $('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append(
                '<div class="row  col-md-12 col-xs-12 search-competence-div">' +
                    '<div class="row col-xs-6 col-md-6">' +
                        '<div class="input-group stylish-input-group input-append ">'+
                            '<input type="text" name="search_competence" class="form-control"'+
                                    'placeholder="Buscar competência" id="search_competence">'+
                            '<span class="input-group-addon">' +
                                '<span class="glyphicon glyphicon-search"></span>' +
                            '</span>' +
                        '</div>' +

                    '</div>'+

                        '<a href="#" accesskey="'+ accesskey +'" data-parentid="'+parentId+'" class="remove_sub_competence_search_box exit-btn pull-right">' +
                            '<span class="glyphicon glyphicon-remove btn-danger">' +
                            '</span>' +
                        '</a>' +
                '</div>'
            );
            $("#accordion #search_competence").autocomplete({
                source: function (request, response) {
                    $.ajax({

                        url: src_competence,
                        dataType: "json",
                        data: {
                            term: request.term,
                        },
                        success: function (data) {
                            response(data);

                        }
                    });
                },
                minLength: 2,
                select: function (e, ui) {
                    $(this).closest('.search-competence-div').remove();
                    console.log(ui.item.value+" - "+ui.item.id);
                    addNewSubCompetenceFromDatabase(false, accesskey, parentId, ui.item.id, ui.item.value, ui.item.description);
                    $(this).val('');
                    return false;
                }

            });


        });
        $(wrapper).on("click",".remove_field", function(e){
            e.preventDefault();
            $(this).parent('div').remove();y--;
        });
        $(wrapper).on("click",".remove_sub_competence_individual_fields", function(e){
            e.preventDefault();
            var accesskey = $(this).attr('accesskey');
            var parentId = $(this).attr('data-parentid');
            $(this).closest('table').remove();
            console.log("accessKey "+accesskey+" parent id= "+parentId);
            //$('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append("belexaaaa");
            $('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append(
                '<div class="childCategoryOptionButtons">' +
                '<div class="col-sm-2 ">' +
                '<a class="btn btn-xs btn-primary" accesskey="'+ accesskey +'" data-parentid="'+parentId+'" id="addNewSubCompetence" ><span class="glyphicon glyphicon-plus"></span> Adicionar nova subcompetência</a>' +
                '</div>' +
                '<div class="col-sm-offset-1 col-md-2">' +
                '<a class="btn btn-xs btn-primary" accesskey="'+ accesskey +'" data-parentid="'+parentId+'" id="addSubCompetenceFromDatabase" ><span class="glyphicon glyphicon-plus"></span> Adicionar subcompetência já cadastrada</a>' +
                '</div>' +
                '</div>'
            );
            //$(this).parent('div').remove();y--;
        });
        $(wrapper).on("click",".remove_sub_competence_search_box", function(e){
            e.preventDefault();
            var accesskey = $(this).attr('accesskey');
            var parentId = $(this).attr('data-parentid');
            $(this).closest('.search-competence-div').remove();
            //$(this).parent('div').remove();
            console.log("accessKeyo "+accesskey+" parent ido= "+parentId);
            //$('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append("belexaaaa");
            $('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append(
                '<div class="childCategoryOptionButtons">' +
                '<div class="col-sm-2 ">' +
                '<a class="btn btn-xs btn-primary" accesskey="'+ accesskey +'" data-parentid="'+parentId+'" id="addNewSubCompetence" ><span class="glyphicon glyphicon-plus"></span> Adicionar nova subcompetência</a>' +
                '</div>' +
                '<div class="col-sm-offset-1 col-md-2">' +
                '<a class="btn btn-xs btn-primary" accesskey="'+ accesskey +'" data-parentid="'+parentId+'" id="addSubCompetenceFromDatabase" ><span class="glyphicon glyphicon-plus"></span> Adicionar subcompetência já cadastrada</a>' +
                '</div>' +
                '</div>'
            );
            //$(this).parent('div').remove();y--;
        });
        $(wrapper).on("click",".remove_ctg_panel", function(e){
            e.preventDefault();
            var accesskey = $(this).attr('accesskey');
            $('#panel'+accesskey).remove();
            counter--;
            //x--;
        });

    });

</script>