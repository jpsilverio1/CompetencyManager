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
                                                            <input type="number" class="form-control" name="competence_ui_id[]" value="1">
                                                            <input type="number" class="form-control" name="parent_ui_id[]" min="-1" value="-1">
                                                            <input type="hidden" class="form-control" name="isNewCompetence[]" value="true">
                                                            @if ($errors->has('description.0'))
                                                                <span class="help-block">
                                        <strong>{{ $errors->first('description.0') }}</strong>
                                    </span>
                                                            @endif
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
<script>

    $(document).ready(function(){
        var competencyIndex = 0;
        $('.addButton').click(function(){
            if (competencyIndex <9) {
                competencyIndex++;
                console.log(competencyIndex);
                var $template = $('#competenceTemplate'),
                    $clone    = $template
                        .clone()
                        .removeClass('hide')
                        .removeAttr('id')
                        .insertBefore($template);
                $clone.find('[name="name"]').attr('name', 'competency[' + competencyIndex + '].name').end()
                    .find('[name="description"]').attr('name', 'competency[' + competencyIndex + '].description').end();
            }
            else {
                alert("Não é possível criar mais de 10 competências por vez");
            }


        });

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
        var currentId = 2;
        var counter = 2;
        var wrapper = $("#accordion");
        $("#addRootCompetenceButton").on("click", function(e){
            e.preventDefault();
            var catgName = 'Competência - '+counter;
            if(counter<11){
                var ariaExpanded = true;
                var expandedClass = 'in';
                var collapsedClass = '';
                $(wrapper).append('<div class="col-sm-12" style="margin-bottom: 0;">' +
                    '<div class="panel panel-default"  id="panel'+ counter +'">' +
                    '<div class="panel-heading" role="tab" id="heading'+ counter +'">' +
                    '<h4 class="panel-title">' +
                    '<a class="" id="panel-lebel'+ counter +'"  role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapse'+ counter +'" ' +
                    'aria-expanded="'+ariaExpanded+'" aria-controls="collapse'+ counter +'"> Competencia '+counter +
                    '</a>' +
                    '<div class="actions_div" style="position: relative; top: -26px;">' +
                    '<a href="#" accesskey="'+ counter +'" class="remove_ctg_panel exit-btn pull-right">' +
                    '<span class="glyphicon glyphicon-remove">' +
                    '</span>' +
                    '</a>' +
                    '<a href="#" accesskey="'+ counter +'"  class="pull-right" id="addChildCompetence">' +
                    '<span class="glyphicon glyphicon-plus">' +
                    '</span>Adicionar subcompetência' +
                    '</a>' +
                    '</div>' +
                    '</h4>' +
                    '</div>' +
                    '<div id="collapse'+ counter +'" class="panel-collapse collapse '+expandedClass+'"role="tabpanel" aria-labelledby="heading'+ counter +'">'+
                    '<div class="panel-body">' +
                    '<table class="table table-striped task-table" class="addCompetencesTable">' +
                    '<tbody>' +
                    '<tr>' +
                    '<td class="form-group col-md-5">' +
                    '<div class="col-md-offset-1">' +
                    '<input type="text" class="form-control" name="name[]" placeholder="Nome da competência"' +
                    '</div>' +
                    '</td>' +
                    '<td class="form-group col-md-5 col-md-offset-2">' +
                    '<div>' +
                    '<input type="text" class="form-control" name="description[]" placeholder="Descrição da competência">' +
                    '<input type="number" class="form-control" name="competence_ui_id[]" value="'+counter+'">' +
                    '<input type="number" class="form-control" name="parent_ui_id[]" min="-1" value="-1">' +
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
                counter++;
                currentId++;
            } else {
                alert("Não é possível criar mais de 10 competências por vez");
            }

        });
        $(wrapper).on("click","#addChildCompetence", function(e){
            e.preventDefault();
            var parentId = $(this).attr('accesskey');

        });
        var x = 1;
        $(wrapper).on("click","#addChildCompetence", function(e){
            e.preventDefault();
            var parentId = $(this).attr('accesskey');
            var parentPanel = '#panel'+ parentId;
            var ariaExpanded = false;
            var expandedClass = '';
            var collapsedClass = 'collapsed';
            var catgName = "Subcompetência - "+counter;
            $(wrapper).find(parentPanel).append('<div class="col-sm-12" style="margin-bottom: 0;">' +
                '<div class="panel panel-default" id="panel'+counter+'">' +
                    '<div class="panel-heading" role="tab" id="heading'+counter+'">' +
                        '<h4 class="panel-title">' +
                        '<a class="'+collapsedClass+'" id="panel-lebel'+ counter +'" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'+ counter+'" ' +
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
                                    '<a class="btn btn-xs btn-primary" accesskey="'+ counter +'" id="addSubCompetenceFromDatabase" ><span class="glyphicon glyphicon-plus"></span> Adicionar subcompetência já cadastrada</a>' +
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
            $('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append(
                '<table class="table table-striped task-table" class="addCompetencesTable">' +
                '<tbody>' +
                '<tr>' +
                '<td class="form-group col-md-5">' +
                '<div class="col-md-offset-1">' +
                '<input type="text" class="form-control" name="name[]" placeholder="Nome da competência"' +
                '</div>' +
                '</td>' +
                '<td class="form-group col-md-5 col-md-offset-2">' +
                '<div>' +
                '<input type="text" class="form-control" name="description[]" placeholder="Descrição da competência">' +
                '<input type="number" class="form-control" name="competence_ui_id[]" value="'+accesskey+'">' +
                '<input type="number" class="form-control" name="parent_ui_id[]" min="-1" value="'+parentId+'">' +
                '<input type="hidden" class="form-control" name="isNewCompetence[]" value="true">' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>'
            );


        });
        $(wrapper).on("click","#addSubCompetenceFromDatabase", function(e){
            e.preventDefault();
            console.log("add from database");
            var accesskey = $(this).attr('accesskey');
            y++;

            $('#panel'+accesskey).find('#TextBoxDiv'+accesskey).append('<div class="col-md-12 form-group"><input type="text" name="ctgtext[]" class="form-control" style="width: 40%;float: left;"/><a href="#" class="remove_field exit-btn"><span class="glyphicon glyphicon-remove"></a></div>');

        });
        $(wrapper).on("click",".remove_field", function(e){
            e.preventDefault();
            $(this).parent('div').remove();y--;
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