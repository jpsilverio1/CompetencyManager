@extends('layouts.app')
@section('content')
<script>
    var dictionary;
    function getLabelForSliderValue(val) {
        return dictionary[val];
    }
    function updateTextInput(slider) {
        var rowHit = $(slider).parent().parent().parent();
        var sliderLabel = rowHit.find(".competence_level_label");
        var newLabel = getLabelForSliderValue(slider.value);
        sliderLabel.html(newLabel);
    }
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url = "{{ route('competence-proficiency-level') }}";
        dictionary = function () {
            var tmp = null;
            $.ajax({
                'async': false,
                'type': "GET",
                'global': false,
                'url': url,
                'success': function (data) {
                    tmp = data;
                }
            });
            return tmp;
        }();
		document.getElementById("sliderName").innerHTML = getLabelForSliderValue(1);
    });
</script>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
                    {{$competence->name}}
                </h2>
            </div>
            <div class="panel-body">
				@if (!empty($message) > 0)
                    <div class="alert alert-success">
                        {{$message}}<br />
						</div>
                @endif
               <h4>
                   Descrição
               </h4>
                <p>{{$competence->description}}</p>
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            Árvore
                        </div>
                        <div class="panel-heading container-fluid" >

                            @include('competences.show_competence_subtree')
                        </div>
                    </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                            Usuários que possuem a competência
                    </div>
                    <div class="panel-body">
                        @include('users.show_paginated_users', ['users' => $competence->skilledUsers()->paginate(10, ['*'],'users'), 'showCompetenceLevel' => True])
                    </div>
                </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Editar ou adicionar competência ao seu perfil
                        </div>
                        <div class="panel-body">
                            Selecione seu nível de conhecimento para esta competência
                            <?php
                            $userHasThisCompetence = Auth::user()->hasCompetence($competence->id);
                            $numberOfCategories = \App\CompetenceProficiencyLevel::count();
                            ?>
                            <form action="/user-competence" method="POST">
                                {{ csrf_field() }}
                                <div class="row cl-md-7">
                                    <input type="hidden" name="name" value="{{$competence->name}}" />
                                    <input type="hidden" name="competence_id" value="{{$competence->id}}" />
                                    <div class="competency_level col-md-4">
                                        <span class="competence_level_label" name="levels[]" ><div id="sliderName"></div></span>
                                        <input type="range" class="competence_level_slider"
                                               name="competence_proficiency_level" min="1" max="{{ $numberOfCategories }}" value="1" onchange="updateTextInput(this)">
                                    </div>
                                    <div class="col-md-3">
                                        @if ($userHasThisCompetence)
                                            Você possui o seguinte nível nesta competência:
                                            {{Auth::user()->competences()->where('competence_id',$competence->id)->first()->pivot->proficiency_level_name}}
                                            <button type="submit" class="btn btn-primary">Alterar Nível</button>
                                        @else
                                            <button type="submit" class="btn btn-primary">Adicionar esta competência ao seu Perfil</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Tarefas que necessitam desta competência
                    </div>
                    @include('tasks.show_paginated_tasks', ['tasks' => $competence->tasksThatRequireIt()->paginate(10, ['*'],'tasks'), 'noTasksMessage' => 'Não há tarefas para exibição.'])
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" >
                        Treinamentos que ensinam esta competência
                        @include('learningaids.show_paginated_learningaids', ['learningAids' => $competence->learningAidsThatRequireIt()->paginate(10, ['*'],'learningaids'), 'noLearningAidsMessage' => 'Não há treinamentos para exibição.'])
                    </div>
                </div>
                    <div>
                        @if (Auth::user()->isManager())
                        <div class="col-md-2">
                            <a href='{{ route('competences.edit', $competence->id) }}'/><button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                        <div>
                            <form  id="deleteCompetencesForm" role="form" method="POST" action="{{ route('competences.destroy', $competence->id ) }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE" />
                                <td><button type="" class="btn btn-danger">Excluir</button></td>
                            </form>
                        </div>
                        @endif
                    </div>
            </div>
        </div>
    </div>
@endsection

