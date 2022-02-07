@extends('layouts.app')

@section('content')

    {{-- {{ Html::script('./js/scripts/exibe_senha.js') }}
    {{ Html::script('./js/scripts/pega_estados_municipios.js') }}

    <div class="container mt-3 p-5" style="background-color:#b0b0b0; ">
        <h2>Cadastro</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            @include('cadastro/formulario')
            
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div> --}}

@endsection
