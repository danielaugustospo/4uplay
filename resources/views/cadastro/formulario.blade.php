<div class="form-row">
    <div class="form-group col-md-12">
        <label for="name">Nome</label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
            value=" @if(@isset($user)) {{ $user->name }}  @else {{ old('name') }}  @endif " required autocomplete="name" autofocus @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
        {{-- {!! Form::text('name', null, array('placeholder' => 'Nome', 'class' => "form-control")) !!} --}}

        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    {{-- <div class="form-group col-md-6">
        <label for="rzsocial">Razão Social</label>
        <input type="text" class="form-control" name="rzsocial" id="rzsocial" placeholder="Razão Social"
            value="@if(@isset($user)){{ $user->rzsocial }}  @else {{ old('rzsocial') }}  @endif " @if(@isset($user)) readonly @endif >
    </div> --}}
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="@if(@isset($user)){{ $user->email }}  @else {{ old('email') }}  @endif" required autocomplete="email" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="password">Senha</label>
        <div class="input-group" id="mostra_senha">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" @if(Route::currentRouteName() != 'users.edit') required @endif  autocomplete="new-password" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
            <a href=""><i class="fa fa-eye-slash form-control p-2" aria-hidden="true"></i></a>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="password-confirm">Repita a Senha</label>
        <div class="input-group" id="mostra_senha_confirmacao">
            <input id="password-confirm" type="password" class="form-control" name="confirm-password" @if(Route::currentRouteName() != 'users.edit') required @endif
                autocomplete="new-password" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
            <a href=""><i class="fa fa-eye-slash form-control p-2" aria-hidden="true"></i></a>
        </div>

    </div>
</div>

<div class="form-row">
    {{-- <div class="form-group col-md-4">
        <label for="cnpj">CNPJ</label>
        <input type="text" class="form-control" name="cnpj" id="cpfcnpj" placeholder="CNPJ"
            value="@if(@isset($user)){{ $user->cnpj }}  @else {{ old('cnpj') }}  @endif" @if(@isset($user)) readonly @endif>
    </div> --}}
    <div class="form-group col-md-12">
        <label for="endereco">Endereço</label>
        <input type="text" class="form-control" name="endereco" placeholder="Endereço"
            value="@if(@isset($user)){{ $user->endereco }}  @else {{ old('endereco') }}  @endif" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control" name="telefone" maxlength="11" placeholder="Telefone"
            value="@if(@isset($user)){{ $user->telefone }}  @else {{ old('telefone') }}  @endif" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
    </div>
    <div class="form-group col-md-6">
        <label for="wpp">WhatsApp</label>
        <input type="text" class="form-control" maxlength="11" name="wpp" placeholder="WhatsApp" value="@if(@isset($user)){{ $user->wpp }}  @else {{ old('wpp') }}  @endif" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) readonly @endif>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="estado">Estado</label>
        <select id="estados" name="estado" class="form-control" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) disabled @endif>

            @if(@isset($user))
                @if ($user->estado)
                    <option value="{{ $user->estado }}" selected>{{ $user->estado }}</option>
                @endif
            @endif

            <option value=""></option>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label for="municipio">Município</label>
        <select id="cidades" class="form-control" name="municipio" @if(@isset($user)  && (Route::currentRouteName() != 'users.edit')) disabled @endif>
            @if(@isset($user))
                @if ($user->municipio)
                    <option value="{{ $user->municipio }}" selected>{{ $user->municipio }}</option>
                @endif
            @endif
        </select>
    </div>
    {{-- <div class="form-group col-md-5">
        <label for="resmunicipio">Reside no mesmo município aonde irá ser licenciado?</label>
        <select id="resmunicipio" name="resmunicipio" class="form-control col-sm-3" @if(@isset($user)) disabled @endif>
            <option @if(@isset($user)) @if($user->resmunicipio == '0')  selected @endif @endif value="0">NÃO</option>
            <option @if(@isset($user)) @if($user->resmunicipio == '1')  selected @endif @endif value="1">SIM</option>
        </select>
    </div> --}}
</div>



<input type="hidden" name="resmunicipio" value="1">

<input type="hidden" class="form-control" name="rzsocial" id="rzsocial" placeholder="Razão Social"
value="@if(@isset($user)){{ $user->rzsocial }}  @else 0  @endif " @if(@isset($user)) readonly @endif >

<input type="hidden" class="form-control" name="cnpj" id="cpfcnpj" placeholder="CNPJ"
value="@if(@isset($user)){{ $user->cnpj }}  @else 0000000  @endif" @if(@isset($user)) readonly @endif>
