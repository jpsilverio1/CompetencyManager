@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center text-capitalize" >
                <h2>
					{{ $task->title }}
                </h2>
				<h3>
					Questionário sobre Colaboração entre Membros da Tarefa
				</h3>
            </div>
            <div class="panel-body">
				<form class="form-horizontal" id="answersForm" role="form" method="POST" action="/task-answer-form">
				{{ csrf_field() }}
					@if (!empty($message) > 0)
						<div class="alert alert-success">
							{{$message}}<br />
							</div>
					@endif
					@if (Session::has('message'))
					   <div class="alert alert-success">
						  {{Session::get('message')}}<br />
					   </div>
					@endif
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							O preenchimento é obrigatório para todas as perguntas.<br />
						</div>
					@endif
					<?php $userInThisTask = $task->members(); $questions = \App\Question::all(); $personalCompetencies = \App\PersonalCompetence::all(); $answerLevels= \App\PersonalCompetenceProficiencyLevel::all(); ?>
					@for ($indiceUsuario = 0; $indiceUsuario < count($userInThisTask); $indiceUsuario++)
						<?php $user = $userInThisTask[$indiceUsuario]; ?>
						<center><h4>Avaliação do Usuário: <b>{{ $user->name }}</b></h4></center>
						@for ($indiceQuestao = 0; $indiceQuestao < count($questions); $indiceQuestao++)
							<?php $personal_competence_level_id[$indiceUsuario][$indiceQuestao] = null; ?>
							<div class="panel panel-default">
								<div class="panel-heading" >
									{!! $questions[$indiceQuestao]->description !!}
									
								</div>
								<div class="panel-body">
									
									@if ($errors->has('personal_competence_level_id'.$indiceUsuario.$indiceQuestao))
										<span class="help-block alert alert-danger">
											<strong>{{ $errors->first('personal_competence_level_id'.$indiceUsuario.$indiceQuestao) }}</strong>
										</span>
                                    @endif
									
									
									
									<input id="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" type="radio" name="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" value="{{$answerLevels[0]->id}}" {{ old('personal_competence_level_id'.$indiceUsuario.$indiceQuestao)== $answerLevels[0]->id ? 'checked' : '' }} > {{ $answerLevels[0]->name }} <br/>
									<input id="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" type="radio" name="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" value="{{$answerLevels[1]->id}}" {{ old('personal_competence_level_id'.$indiceUsuario.$indiceQuestao)== $answerLevels[1]->id ? 'checked' : '' }} > {{ $answerLevels[1]->name }} <br/>
									<input id="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" type="radio" name="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" value="{{$answerLevels[2]->id}}" {{ old('personal_competence_level_id'.$indiceUsuario.$indiceQuestao)== $answerLevels[2]->id ? 'checked' : '' }} > {{ $answerLevels[2]->name }} <br/>
									<input id="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" type="radio" name="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" value="{{$answerLevels[3]->id}}" {{ old('personal_competence_level_id'.$indiceUsuario.$indiceQuestao)== $answerLevels[3]->id ? 'checked' : '' }} > {{ $answerLevels[3]->name }} <br/>
									<input id="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" type="radio" name="personal_competence_level_id{{$indiceUsuario}}{{$indiceQuestao}}" value="{{$answerLevels[4]->id}}" {{ old('personal_competence_level_id'.$indiceUsuario.$indiceQuestao)== $answerLevels[4]->id ? 'checked' : '' }} > {{ $answerLevels[4]->name }} <br/>
									
									<small><a href="#" data-toggle="myToolTip" data-placement="top"  data-trigger="click" data-html="true"  title="{{ $personalCompetencies[$indiceQuestao]['description'] }}">O que significa esta competência?</a></small> <br/>
				
								</div>
							</div>
						@endfor
						<input id="evaluated_user_id" type="hidden" class="form-control" name="evaluated_user_id[]" value="{{$user->id}}" >
						<br/>
					@endfor
					
					<?php $personalCompetenciesIds = \App\PersonalCompetence::all(['id'])->toArray(); ?>
					@for ($k = 0; $k < count($personalCompetenciesIds); $k++)
						<?php $personalComp = $personalCompetenciesIds[$k]; ?>
						<input id="personal_competence_id{{$personalComp['id']}}" type="hidden" class="form-control" name="personal_competence_id[]" value="{{$personalComp['id']}}" >
					@endfor
					
					<div class="otherElements">
						
						<input id="judge_user_id" type="hidden" class="form-control" name="judge_user_id" value="{{ \Auth::user()->id }}" >
						<input id="task_id" type="hidden" class="form-control" name="task_id" value="{{ $task->id }}" >
					</div>
					
					<td><button type="submit" class="btn btn-primary">Enviar Respostas</button></td>
					
				</form>	
				<td><a href='/tasks/{{ $task->id }} '/><button class="btn btn-primary">Voltar</button></td>	

							
            </div>
        </div>
    </div>
@endsection