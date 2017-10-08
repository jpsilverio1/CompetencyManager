@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="col-md-6">
            <div class="panel panel-default">

                <div class="panel-heading">Treinamentos</div>

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

                    @include('learningaids.show_paginated_learningaids', ['noLearningAidsMessage' => 'Não há treinamentos para exibição.'])
                </div>
            </div>
        </div>
    </div>


@endsection
