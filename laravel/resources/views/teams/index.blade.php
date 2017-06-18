@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Equipes</div>

                <div class="panel-body">
				
					@if (!empty($message) > 0)
                        <div class="alert alert-success">
                            {{$message}}<br />
                        </div>
                    @endif
				
                    @include('teams.show_paginated_teams')
                </div>
            </div>
        </div>
    </div>
@endsection