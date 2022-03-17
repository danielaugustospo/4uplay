@extends('layouts.app')
@section('content')


<div class="container">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Altere sua senha</div>
   
                <div class="card-body">
                    <form method="POST" action="{{ route('trocarsenha') }}">
                        @csrf 
   
                         @foreach ($errors->all() as $error)
                            <p class="text-danger">{{ $error }}</p>
                         @endforeach 
  
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha atual</label>
  
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" value="{{ old('current_password') }}"   name="current_password" autocomplete="current-password"><input type="checkbox" onclick="verSenhaAtual()">&nbsp;Exibir senha atual
                            </div>
                        </div>
  
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Nova Senha</label>
  
                            <div class="col-md-6">
                                <input id="nova_senha" type="password" class="form-control" value="{{ old('nova_senha') }}" name="nova_senha" autocomplete="current-password"><input type="checkbox" onclick="verNovaSenha()">&nbsp;Exibir nova senha
                            </div>
                        </div>
  
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Confirme a nova senha</label>
    
                            <div class="col-md-6">
                                <input id="new_confirm_password" type="password" class="form-control" value="{{ old('confirme_a_senha') }}" name="confirme_a_senha" autocomplete="current-password"><input type="checkbox" onclick="verConfirmacaoSenha()">&nbsp;Exibir confirma&ccedil;&atilde;o da senha
                            </div>
                        </div>
   
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Mudar Senha
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
function verSenhaAtual() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function verNovaSenha() {
  var x = document.getElementById("nova_senha");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function verConfirmacaoSenha() {
  var x = document.getElementById("new_confirm_password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
@endsection