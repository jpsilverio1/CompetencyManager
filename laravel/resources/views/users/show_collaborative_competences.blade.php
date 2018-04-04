<div class="panel panel-default">
    <div class="panel-heading">Competências Colaborativas de {{$user->name}}</div>

    <div class="panel-body">
		<table class="table table-striped task-table" id="showCompetencesTable">
			<thead >
				<th>Competência Colaborativa</th>
				<th>Avaliação média recebida por outros Usuários</th>
			</thead>

			<!-- Table Body -->
			<tbody>
				<?php $collaborative_competences_for_this_user = $user->collaborativeCompetencesWithAverageLevel(); ?>
				@foreach ($collaborative_competences_for_this_user as $competence)
					<tr>
						<!-- Task Name -->
						<td class="table-text">
							<div><a href="" data-toggle="myToolTip" data-placement="top"  data-trigger="click" data-html="true"  title="{{ $competence->description }}">{{ $competence->name }}</a></div>
						</td>
						<td>
							{{ $competence->avg_collab_level }}
						</td>
					</tr>


				@endforeach
			</tbody>
		</table>
	</div>
</div>

