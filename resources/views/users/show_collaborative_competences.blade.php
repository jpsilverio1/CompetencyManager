<div class="panel panel-default">
    <div class="panel-heading">Competências Colaborativas de {{$user->name}}</div>

    <div class="panel-body">
		@php($collaborative_competences_for_this_user = $user->collaborativeCompetencesWithAverageLevel())
		@if(count($collaborative_competences_for_this_user) > 0)
			<table class="table table-striped task-table" id="showCompetencesTable">
				<h4> Nível médio de colaboraçao: {{number_format($collaborative_competences_for_this_user->pluck('avg_collab_level')->avg()*100,2)}}% </h4>

				<thead >
					<th>Competência Colaborativa</th>
					<th>Avaliação média recebida por outros Usuários</th>
				</thead>

				<!-- Table Body -->
				<tbody>
					@foreach ($collaborative_competences_for_this_user as $competence)
						<tr>
							<!-- Task Name -->
							<td class="table-text">
								<div><a href="" data-toggle="myToolTip" data-placement="right"  data-html="true"  title="{{ $competence->description }}">{{ $competence->name }}</a></div>
							</td>
							<td>
								{{number_format($competence->avg_collab_level*100,2)}}%
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			Ainda não há avaliações suficientes para exibição das estatísticas de colaboração deste usuário.
		@endif
	</div>
</div>
<script>
    $(document).ready(function () {
        $('[data-toggle="myToolTip"]').tooltip({
            html:"true"
        });
    });
</script>

