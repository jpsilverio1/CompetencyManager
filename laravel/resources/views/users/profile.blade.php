@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="span3 well well-sm">
            <center>
               <!-- <a href="#aboutModal" data-toggle="modal" data-target="#myModal"><img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRbezqZpEuwGSvitKy3wrwnth5kysKdRqBW54cAszm_wiutku3R" name="aboutme" width="140" height="140" class="img-circle"></a>
                -->
                <h3>{{ $user->name }}</h3>
                <div class="profile-usertitle-job text-capitalize">
                    {{ $user->level }}
                </div>
                <i class="icon-envelope"></i> {{$user->email}} <br>
            </center>
        </div>
        <!--
        <div class="row">
            <div class="col-sm-10"><h2>{{ $user->name }}</h2></div>
        </div> -->
        <div class="row">
            @include('users.show_competences_for_endorsement', ['competences' => $user->competencies, 'profile_user' => $user])
        </div>
    </div>
@endsection