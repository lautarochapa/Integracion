<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Linea 161</title>
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('css/welcome.css') }}" defer></script>
    </head>
    <body>


<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                    @auth
                    <a class="navbar-brand" href="{{ url('/branchesView') }}">
                    Ramales
                </a>
                <a class="navbar-brand" href="{{ url('/stopsView') }}">
                Paradas
                </a>
                    @else
                    @endauth
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
        </nav>



        <div class="flex-center position-ref full-height">
            <div class="content">
            
                <div class="title m-b-md">
                    Linea 161    
                </div>
                <div>
                    <img src="{{ asset('images/bondi.png') }}" alt="Ilustracion Colectivo" width="50%">
                </div>
                <br><br>
                <a href="#posicionMapa">mapa</a>
                <div class="links">
                    @auth
                    <a href="{{ url('/branchesView') }}">Ramales</a>
                    <a href="{{ url('/stopsView') }}">Paradas</a>
                    @else
                    @endauth
                </div>
                <br>
               
            </div>
        </div>
        <a name="posicionMapa" id="posicionMapa"></a>
        <br><br><br><br>
        <div class="flex-center" id="branchesSelect">
                        <div class="dropdown">
                            <button class="dropbtn">Ramales</button>
                            <div class="dropdown-content">
                                <div v-for="branch in branches">
                                    <a v-bind:href="'{{ url('/') }}?branch=' + branch.id+'#posicionMapa'">
                                        @{{ branch.name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
        
        <br>
        
        <div class="container" id="contenedormap" >


        
            <br>
            <div id="map"></div>
            

           
            <script src="{{ asset('js/welcome.js') }}" defer></script>
            <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLDSPbOx2b4QKAgdY6qRa4LdBcg1tm-xs&callback=crearMapa">
            </script>
        </div>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    </body>
</html>
