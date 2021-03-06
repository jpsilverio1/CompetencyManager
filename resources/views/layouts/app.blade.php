<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/myStyle.css') }}" rel="stylesheet">
    <link href="{{asset('css/competence-proficiency-level-colors.css')}}" rel="stylesheet">
    <link href="{{ asset('css/competence-subtree.css') }}" rel="stylesheet">
    <link href="{{ asset('css/create-competence.css') }}" rel="stylesheet">
    <!-- Styles from task-team-creation -->
    <link href="{{ asset('css/task-team-creation-assistant.css') }}" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/> -->

    <script src="{{ asset('js/competences/competence-proficiency-level.js') }}"></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp; <li class="active"><a href="{{ route('home') }}">Início</a></li>
                        @if (!Auth::guest())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Visualizar <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('competences.index') }}">Competências</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{ route('users.index') }}">Usuários</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{ route('tasks.index') }}">Tarefas</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{ route('learningaids.index') }}">Treinamentos</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{ route('jobroles.index') }}">Cargos</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastrar <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    @if (Auth::user()->isManager())
                                        <li><a href="{{ route('competences.create')}}">Competências</a></li>
                                    @endif
                                   <li class="divider"></li>
                                    <li><a href="{{ route('tasks.create') }}">Tarefa</a></li>
                                    @if (Auth::user()->isManager())
                                        <li class="divider"></li>
                                        <li><a href="{{ route('learningaids.create')}}">Treinamentos</a></li>
                                        <li class="divider"></li>
                                        <li><a href="{{ route('jobroles.create')}}">Cargo</a></li>
                                    @endif
                                </ul>
                            </li>
							@if (Auth::user()->isManager())
								<li class=""><a href="{{ route('dashboards.index')}}">Relatórios</a></li>
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">

                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Registro</a></li>
                        @else

                           <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="glyphicon glyphicon-bell"></i>
                                    <span class="badge badge-light">{{auth()->user()->unreadNotifications->count()}}</span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    @if(auth()->user()->unreadNotifications->count() == 0)
                                        <li><a>Sem notificações</a></li>
                                    @else
                                        @foreach(auth()->user()->unreadNotifications as $notification)
                                            <li><a href="{{ route( 'show-task-form', $notification->data['id']) }}">Tarefa "{{$notification->data['title']}}" terminada</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>


                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
									<li><a href="{{ route('users.show', Auth::user()->id) }}">Seu perfil</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                    @if (!Auth::guest())
                        @include('users.search_user')
                    @endif
                </div>
            </div>
        </nav>
        @if(count($globalCompetenceProficiencyLevels) > 0)
            <ul hidden class="competence-proficiency-level-labels">
                @foreach($globalCompetenceProficiencyLevels as $competenceProficiencyLevel)
                    <li>{{$competenceProficiencyLevel->name}}</li>
                @endforeach
            </ul>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->

   <!-- <script src="{{ asset('js/app.js') }}"></script> -->
</body>
</html>
