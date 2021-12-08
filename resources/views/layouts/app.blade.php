<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gerencimento - 4UPlay') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Cadastro') }}</a></li>
                        @else
                            @can('lista-cadastro')
                                <li><a class="nav-link" href="cadastro">Cadastro</a></li>
                            @endcan
                            @can('lista-pipeline')
                            <li><a class="nav-link" href="pipeline">Pipeline</a></li>
                            @endcan
                            @can('lista-criativo')
                                <li><a class="nav-link" href="criativo">Criativo</a></li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRelatorio" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Relatório
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownRelatorio">
                                @can('relatorio-financeiro')
                                    <a class="dropdown-item" href="relfinanceiro">Financeiro</a>
                                @endcan
                                @can('relatorio-sintetico')
                                    <a class="dropdown-item" href="relsintetico">Sintético</a>
                                @endcan
                                </div>
                            </li>
                            @can('role-list')
                                <li><a class="nav-link" href="{{ route('users.index') }}">Gerenciar Usuários</a></li>
                                <li><a class="nav-link" href="{{ route('roles.index') }}">Permissões</a></li>
                            @endcan
                            @can('product-list')
                                <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>


                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
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