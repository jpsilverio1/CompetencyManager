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
					<?php $userInThisTask = $task->members(); $questions = \App\Question::all(); $personalCompetenciesIds = \App\PersonalCompetence::get('id'); $personalCompetencies = \App\PersonalCompetence::all(); $answerLevels= \App\PersonalCompetenceProficiencyLevel::all(); ?>
					@for ($x = 0; $x < count($userInThisTask); $x++)
						<?php $user = $userInThisTask[$x]; ?>
						<center><h4>Avaliação do Usuário: <b>{{ $user->name }}</b></h4></center>
						@for ($i = 0; $i < count($questions); $i++)
							<?php $personal_competence_level_id[$x][$i] = null; ?>
							<div class="panel panel-default">
								<div class="panel-heading" >
									{!! $questions[$i]->description !!}
									
								</div>
								<div class="panel-body">
									@if ($errors->has('title'))
										<span class="help-block">
											<strong>{{ $errors->first('title') }}</strong>
										</span>
									@endif
									<input type="hidden" name="personal_competence_level_id[{{$x}}][{{$i}}]" value="{{ old('$personal_competence_level_id[$x][$i]') ? old('$personal_competence_level_id[$x][$i]') : Null }}" >
									<input id="$personal_competence_level_id[{{$x}}][{{$i}}]" type="radio" name="personal_competence_level_id[{{$x}}][{{$i}}]" value="{{$answerLevels[0]->id}}" {{ old('$personal_competence_level_id[$x][$i]')== $answerLevels[0]->id ? 'checked' : '' }} > {{ $answerLevels[0]->name }} <br/>
									<input id="$personal_competence_level_id[{{$x}}][{{$i}}]" type="radio" name="personal_competence_level_id[{{$x}}][{{$i}}]" value="{{$answerLevels[1]->id}}" {{ old('$personal_competence_level_id[$x][$i]')== $answerLevels[1]->id ? 'checked' : '' }} > {{ $answerLevels[1]->name }} <br/>
									<input id="$personal_competence_level_id[{{$x}}][{{$i}}]" type="radio" name="personal_competence_level_id[{{$x}}][{{$i}}]" value="{{$answerLevels[2]->id}}" {{ old('$personal_competence_level_id[$x][$i]')== $answerLevels[2]->id ? 'checked' : '' }} > {{ $answerLevels[2]->name }} <br/>
									<input id="$personal_competence_level_id[{{$x}}][{{$i}}]" type="radio" name="personal_competence_level_id[{{$x}}][{{$i}}]" value="{{$answerLevels[3]->id}}" {{ old('$personal_competence_level_id[$x][$i]')== $answerLevels[3]->id ? 'checked' : '' }} > {{ $answerLevels[3]->name }} <br/>
									<input id="$personal_competence_level_id[{{$x}}][{{$i}}]" type="radio" name="personal_competence_level_id[{{$x}}][{{$i}}]" value="{{$answerLevels[4]->id}}" {{ old('$personal_competence_level_id[$x][$i]')== $answerLevels[4]->id ? 'checked' : '' }} > {{ $answerLevels[4]->name }} <br/>
									
									
								</div>
							</div>
						@endfor
						<input id="evaluated_user_id" type="hidden" class="form-control" name="evaluated_user_id[]" value="{{$user->id}}" >
						<br/>
					@endfor
					
					<div class="otherElements">
						<input id="personal_competence_id" type="hidden" class="form-control" name="personal_competence_id" value="{{$personalCompetenciesIds}}" >
						<input id="judge_user_id" type="hidden" class="form-control" name="judge_user_id" value="{{ \Auth::user()->id }}" >
						<input id="task_id" type="hidden" class="form-control" name="task_id" value="{{ $task->id }}" >
					</div>
					
					<?php print_r($personal_competence_level_id); ?>
					
					<td><button type="submit" class="btn btn-primary">Enviar Respostas</button></td>
					
				</form>	
				<td><a href='/tasks/{{ $task->id }} '/><button class="btn btn-primary">Voltar</button></td>				
            </div>
        </div>
    </div>
@endsection
