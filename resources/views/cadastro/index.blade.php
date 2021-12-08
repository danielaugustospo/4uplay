@extends('layouts.app')
@section('content')

<div class="container mt-3 p-5" style="background-color:#b0b0b0; ">
<h2>Cadastro</h2>

<form>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputNome">Nome Fantasia</label>
            <input type="text" class="form-control" id="inputNome" placeholder="Nome">
        </div>
        <div class="form-group col-md-6">
            <label for="inputRzSocial">Razão Social</label>
            <input type="text" class="form-control" id="inputRzSocial" placeholder="Razão Social">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputCNPJ">CNPJ</label>
            <input type="text" class="form-control" id="inputCNPJ" placeholder="CNPJ">
        </div>
        <div class="form-group col-md-6">
            <label for="inputEndereco">Endereço</label>
            <input type="text" class="form-control" id="inputEndereco" placeholder="Endereço">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputTelefone">Telefone</label>
            <input type="text" class="form-control" id="inputTelefone" placeholder="Telefone">
        </div>
        <div class="form-group col-md-6">
            <label for="inputWhatsApp">WhatsApp</label>
            <input type="text" class="form-control" id="inputWhatsApp" placeholder="WhatsApp">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="regiaomunicipio">Região de interesse (município)</label>
            <select id="regiaomunicipio" class="form-control">
                @include('cadastro/municipios')
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="regiaomunicipiolicenciado">Reside no mesmo município aonde irá ser licenciado?</label>
            <select id="regiaomunicipiolicenciado" class="form-control">
                <option value="">NÃO</option>
                <option value="">SIM</option>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
</div>

@endsection