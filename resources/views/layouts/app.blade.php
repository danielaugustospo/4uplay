<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gerencimento - 4UPlay') }}</title>
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.1207/styles/kendo.default-main.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    
    {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script> --}}
    
    {{-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
    <script src="https://kendo.cdn.telerik.com/2021.3.1207/js/jquery.min.js"></script>              
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>         --}}
    {{-- <script src="https://kendo.cdn.telerik.com/2021.3.1207/js/kendo.all.min.js"></script> --}}
    <script src="{{ asset('js/kendogrid/kendo.all.min.js') }}" defer></script>
    <script src="{{ asset('js/kendogrid/kendo-messages_pt-br.js') }}" defer></script>
    <script src="{{ asset('js/kendogrid/kendo.culture.pt-BR.min.js') }}" defer></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/kendo-ui-core/2014.1.416/js/cultures/kendo.culture.pt-BR.min.js"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.4.0/jszip.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.1/dist/sweetalert2.all.min.js" integrity="sha256-x7Yk56ZYq7Z6MPePNSTZQn42lokx3xDNDGLhwHUZa7M=" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" defer></script>
    <script src="{{ asset('js/scripts/util.js') }}" defer></script>



    <style>
    td, span {
        font-size: 13;
    }

    .k-grid-header .k-header>.k-link {
        font-size: 13;
    }
    </style>
</head>
<body>
    <div id="app">
   
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
            <img src="{{ url('img/02.01_logo_top_4uplay.png') }}" width="80" alt="" srcset="">

                <!-- <a class="navbar-brand" href="{{ url('/img/02.01_logo_top_4uplay.png') }}">
                    4UPlay                </a> -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Área do Licenciado') }}</a></li>
                            {{-- <li><a class="nav-link" href="{{ route('register') }}">{{ __('Cadastro') }}</a></li> --}}
                        @else

                            @can('lista-pipeline')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRelatorio" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Pipeline
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownRelatorio">
                                @can('pipeline-list')
                                    <a class="dropdown-item" href="{{ route('pipeline') }}">Consultar/Cadastrar</a>
                                @endcan
                                @can('pipeline-historico')
                                    <a class="dropdown-item" href="{{ route('historicopipeline')}}">Histórico</a>
                                @endcan
                                </div>
                            </li>

                            @endcan
                            @can('lista-criativo')
                                <li><a class="nav-link" href="{{ route('criativo2') }}">Criativo</a></li>
                            @endcan
                            @can('mensalidade-list')
                                <li><a class="nav-link" href="{{ route('mensalidade') }}">Mensalidade</a></li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRelatorio" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Relatório
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownRelatorio">
                                @can('relatorio-financeiro')
                                    <a class="dropdown-item" href="{{ route('financeiro2') }}">Financeiro</a>
                                @endcan
                                @can('relatorio-sintetico')
                                    <a class="dropdown-item" href="{{ route('sintetico2') }}">Sintético</a>
                                @endcan
                                </div>
                            </li>
                            @can('role-list')
                                <li><a class="nav-link" href="{{ route('users.index') }}">Gerenciar Usuários</a></li>
                                <li><a class="nav-link" href="{{ route('roles.index') }}">Permissões</a></li>
                            @endcan
                            @can('totem-list')
                                <li><a class="nav-link" href="{{ route('totem') }}">Totens</a></li>
                            @endcan
                            @can('clienteslicenciado-list')
                                <li><a class="nav-link" href="{{ route('clientelicenciado') }}">Clientes</a></li>
                            @endcan
                            @can('product-list')
                                <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>


                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('cadastro2', 'id='.Auth::id()) }}">
                                        Meus Dados
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Sair') }}
                                    </a>


                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <hr style="padding-bottom: 0.5%; margin-top: 0; border-top-width: 0px; background-color: #8A2BE2 !important;">


        <main class="py-4">
            <div class="container">
            @yield('content')
            </div>
        </main>
    </div>
</body>
</html>