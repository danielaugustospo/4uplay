@extends('layouts.app')


@section('content')

{{ Html::script('js/scripts/pega_estados_municipios.js') }}
{{ Html::script('js/scripts/exibe_senha.js') }}

<div class="row">
    <div class="container mt-2 pl-3">
        <div class="pull-left">
            <h2>Cadastrar Novo Usuário</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Voltar</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Atenção!</strong> Reconsidere os seguintes campos:<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif



{!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}

<div class="container mt-3 p-5" style="background-color:#b0b0b0; ">
    @include('cadastro/formulario')


    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissões:</strong>
            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
        </div>
    </div>
    
    <button class="btn btn-primary" type="submit">Salvar</button>
</div>

{{-- <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nome:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Nome','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Senha:</strong>
            {!! Form::password('password', array('placeholder' => 'Senha','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Confirme a senha:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirme a senha','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissões:</strong>
            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</div> --}}
{!! Form::close() !!}

<p class="text-center text-primary"><small>Desenvolvido por danieltecnologia.com</small></p>
@endsection