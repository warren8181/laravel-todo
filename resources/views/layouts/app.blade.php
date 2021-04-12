<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    <script src="../../js/app.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    @notify_css
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    @auth

                        <div class="dropdown nav-button notifications-button hidden-sm-down">

                            <a class="btn  dropdown-toggle" href="#" id="notifications-dropdown" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i id="notificationsIcon" aria-hidden="true"></i>
                                <svg class="bi bi-bell-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="white"
                                    xmins="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z" />
                                </svg>
                                </svg>
                                <span id="notificationBadge" class="badge badge-danger">
                                    <i class="fa fa-spinner fa-pulse fa-fw" aria-hidden="true">
                                        {{ count(Auth::User()->unreadNotifications) }}
                                    </i>

                                </span>
                            </a>

                            <div class="dropdown-menu notification-dropdown-menu btn-white"
                                aria-labelledby="notifications-dropdown" style="min-width: 360px; max-width: 360px">
                                <h6 class="dropdown-header">
                                    Notifications
                                </h6>

                                @if (count(Auth::User()->unreadNotifications) > 0)
                                    <div id="notificationsContainer" class="notifications-container">

                                        @foreach (Auth::User()->unreadNotifications as $notification)
                                            <div class="notifications-body">

                                                <p class="notification-texte mx-1">
                                                    <small>
                                                        <span class="badge badge-pill badge-dark">
                                                            #{{ $notification->data['todo_id'] }}
                                                        </span>

                                                        {{ $notification->data['affected_by'] }}
                                                        t'a affecté la todo {{ $notification->data['todo_name'] }}

                                                    </small>
                                                </p>

                                            </div>

                                            {{ Auth::User()->unreadNotifications->markAsRead() }}
                                        @endforeach

                                    </div>

                                @else

                                    <a id="notificationAucune" class="dropdown-item dropdown-notification" href="#">
                                        <p class="notification-solo text-center">
                                            Aucune nouvelle notification
                                        </p>

                                    </a>

                                @endif

                                {{-- <!-- TOUTES -->
                             <a class="dropdown-item dropdown-notification-all" href="#">
                                 Voir toutes les notifications
                             </a> --}}

                            </div>

                        </div>

                    @endauth

                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a href="{{ route('todos.index') }}" class="nav-link">Les Todos</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('apropos') }}" class="nav-link">A Propos</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>
    </div>
</body>

@notify_js
@notify_render

</html>
