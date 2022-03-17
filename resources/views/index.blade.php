<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>4UPLAY</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

    </head>
    <body>
        
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <b><a href="{{ url('/home') }}">Início</a></b>
                    @else
                        <b><a href="{{ route('login') }}">Área do Licenciado</a></b>

                        {{-- @if (Route::has('register'))
                            <b><a href="{{ route('register') }}">Cadastrar</a></b>
                        @endif --}}
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <a href="{{ url('/home') }}"><img alt="Clique aqui para redirecionar ao sistema" src="https://4uplay.com.br/wp-content/uploads/2021/07/02.01_logo_top_4uplay.png"></a>
                </div>

                <div class="links">
                    <a href="https://4uplay.com.br/">Site</a>
                    <a href="https://www.instagram.com/4uplay/">Instagram</a>
                    <a href="https://www.facebook.com/4uplay-100964642357823/">Facebook</a>
                    {{-- <a href="https://twitter.com/4uplay/">Twitter</a> --}}

                </div>
            </div>
        </div>
    </body>
</html>
