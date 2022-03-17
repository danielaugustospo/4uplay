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
    

    <style>
        td, span { font-size: 11; }
        .k-grid-header .k-header>.k-link { font-size: 11; }
    </style>

    
    @include('layouts/scripts')
    {{-- @include('layouts/include') --}}
    @include('layouts/estilo')
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
                        @can('clienteslicenciado-list')
                            <li><a class="nav-link" href="{{ route('clientelicenciado') }}">Clientes</a></li>
                        @endcan
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
                                    <a class="dropdown-item" onclick="ajustaLinkFormularioFinanceiro()"  data-toggle="modal" data-target="#exampleModal">Financeiro</a>
                                @endcan
                                @can('relatorio-sintetico')
                                    <a class="dropdown-item" onclick="ajustaLinkFormularioSintetico()" data-toggle="modal" data-target="#exampleModal">Sintético</a>
                                @endcan
                                </div>
                            </li>
                            @can('role-list')
                                <li><a class="nav-link" href="{{ route('users.index') }}">Gerenciar Usuários</a></li>
                                <li><a class="nav-link" href="{{ route('roles.index') }}">Permissões</a></li>
                            @endcan
                            @can('totem-create')
                                <li><a class="nav-link" href="{{ route('totem') }}">Totens</a></li>
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
                                    <a class="dropdown-item" href="{{ route('alterasenha')}}">
                                        Alterar minha senha
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


        {{-- <main class="py-4"> --}}
            {{-- <div class="container"> --}}
            @yield('content')
            {{-- </div> --}}
        {{-- </main> --}}
    </div>
    
    {{-- <button onclick="myFunction()">Add Class</button> --}}

    <script>
    function ajustaLinkFormularioSintetico() {
      document.getElementById("formFiltraPeriodoRelatorio").setAttribute("action", "{{ route('sintetico2') }}"); 
    }
    function ajustaLinkFormularioFinanceiro() {
      document.getElementById("formFiltraPeriodoRelatorio").setAttribute("action", "{{ route('financeiro2') }}"); 
    }
    </script>
    
 
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o Período</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="formFiltraPeriodoRelatorio" method="get">
            @csrf
            <div class="modal-body">
                <input type="date" name="datainicial" id="">
                <input type="date" name="datafinal" id="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Buscar</a>
                {{-- <a class="dropdown-item" href="{{ route('sintetico2') }}" data-toggle="modal" data-target="#exampleModal">Sintético</a> --}}
                
            </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
    <style>
        /* Page Template for the exported PDF */
        .page-template {
          font-family: "DejaVu Sans", "Arial", sans-serif;
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
        }
        .page-template .header {
          position: absolute;
          top: 30px;
          left: 30px;
          right: 30px;
          border-bottom: 1px solid #888;
          color: #888;
        }
        .page-template .footer {
          position: absolute;
          bottom: 30px;
          left: 30px;
          right: 30px;
          border-top: 1px solid #888;
          text-align: center;
          color: #888;
        }
        .page-template .watermark {
          font-weight: bold;
          font-size: 400%;
          text-align: center;
          margin-top: 30%;
          color: #aaaaaa;
          opacity: 0.1;
          transform: rotate(-35deg) scale(1.7, 1.5);
        }