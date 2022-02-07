@extends('layouts.app')

@section('content')

    @if (@isset($user))

    @else
        {{ Html::script('./js/scripts/pega_estados_municipios.js') }}
    @endif
        {{ Html::script('./js/scripts/exibe_senha.js') }}
    <div class="container mt-3 p-5" style="background-color:#b0b0b0; ">
        <h2>Cadastro</h2>

        @include('cadastro/formulario')

        </form>
    </div>

@endsection
