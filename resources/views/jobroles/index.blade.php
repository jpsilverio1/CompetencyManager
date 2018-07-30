@extends('layouts.app')
@section('content')


    <div class="container">
		<div class="row">
            @if (!Auth::guest())
                @include('jobroles.search_jobrole')
            @endif
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
				
                <div class="panel-heading">Cargos</div>

                <div class="panel-body">
					
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
					
                    @include('jobroles.show_paginated_jobroles', ['noJobRolesMessage' => 'Não há cargos para exibição.'])
                </div>
            </div>
        </div>
    </div>


@endsection