@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Dados do Usuário {{ $user->name }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" style="background-color: #8A2BE2 !important; border-color: #8A2BE2 !important;" href="{{ route('users.index') }}"> Voltar</a>
                <a class="btn btn-primary" style="background-color: #8A2BE2 !important; border-color: #8A2BE2 !important;" href="{{ route('users.index') }}/{{$user->id}}/edit"> Editar</a>

                {!! Form::open(['method' => 'get', 'route' => ['desativaUsuario', $user->id], 'style' => 'display:inline']) !!}
                {{-- <input type="button" id="btnExclui" value="Excluir"> --}}
                {!! Form::submit('Excluir', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

<hr>
    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nível de Acesso:</strong>
                @if (!empty($user->getRoleNames()))
                    @foreach ($user->getRoleNames() as $v)
                        <label class="badge badge-success">{{ $v }}</label>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@include('cadastro/formulario')


    <p class="text-center text-primary"><small>Desenvolvido por danieltecnologia.com</small></p>
@endsection
