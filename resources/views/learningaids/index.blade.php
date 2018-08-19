@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">
            @if (!Auth::guest())
                @include('learningaids.search_learningaid')
            @endif
                <form class="navbar-form" role="form" method="POST" action="{{ route('learningaids-index') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="sort_type" value="{{$sortType}}">
                    <div class="form-group">
                        <div>
                            @if($sortType == "name")
                                <button type="submit" class="btn btn-info">Ordenar por nome</button>
                            @else
                                <button type="submit" class="btn btn-info">Ordenar por data</button>
                            @endif
                        </div>
                    </div>
                </form>
        </div>
        <div class="col-md-6 row">
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
