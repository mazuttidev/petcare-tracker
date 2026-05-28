@extends('layouts.auth', ['title' => 'Entrar — PetCare Tracker'])

@section('content')

{{-- Cabeçalho da tela --}}
<div style="margin-bottom:28px">
    <div class="pc-h2" style="color:var(--pc-n-900);margin-bottom:6px">Bem-vindo de volta</div>
    <p class="pc-body" style="color:var(--pc-n-500)">
        Entre na sua conta para continuar cuidando dos seus pets.
    </p>
</div>

{{-- Alerta de credenciais inválidas ou erro geral --}}
@if($errors->any())
    <x-alert variant="danger" style="margin-bottom:20px">
        {{ $errors->first() }}
    </x-alert>
@endif
@if(session('error'))
    <x-alert variant="danger" style="margin-bottom:20px">
        {{ session('error') }}
    </x-alert>
@endif

<form id="form-login" method="POST" action="{{ route('login') }}" novalidate>
    @csrf

    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- E-mail --}}
        <x-form.input
            name="email"
            type="email"
            label="E-mail"
            placeholder="seu@email.com"
            :error="$errors->first('email')"
            autocomplete="email"
        />

        {{-- Senha com toggle visibilidade --}}
        <div class="pc-field">
            <label class="pc-field-label" for="password">Senha</label>
            <div class="pc-input-wrap">
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="pc-input{{ $errors->has('password') ? ' pc-input--error' : '' }}"
                    placeholder="••••••••"
                    autocomplete="current-password"
                />
                <button type="button" class="pc-pwd-toggle" id="toggle-pwd" aria-label="Mostrar senha">
                    {{-- Ícone olho (exibido quando senha está oculta) --}}
                    <svg class="icon-show" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    {{-- Ícone olho riscado (exibido quando senha está visível) --}}
                    <svg class="icon-hide" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                         style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="pc-field-error" role="alert">
                    <x-icon name="alert_circle" size="13" aria-hidden="true" />
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Lembrar de mim --}}
        <label class="pc-check-label">
            <input type="checkbox" name="remember" @checked(old('remember'))>
            <span class="pc-checkbox">
                <x-icon name="check" size="13" color="#fff" stroke="3" />
            </span>
            Lembrar de mim
        </label>

    </div>

    <x-btn type="submit" style="width:100%;margin-top:24px" size="lg">
        Entrar
    </x-btn>

</form>

<p class="pc-body" style="text-align:center;margin-top:24px;color:var(--pc-n-500)">
    Não tem uma conta?
    <a href="{{ route('register') }}" class="pc-link">Criar conta grátis</a>
</p>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ─────────────────────────────────────────────────────────────────────────
    // VALIDAÇÃO CLIENTE — APENAS EXPERIÊNCIA DO USUÁRIO
    // A validação real e a segurança estão no servidor (LoginRequest).
    // Este script pode ser desativado ou contornado pelo usuário — nunca confie
    // nele para garantir integridade ou segurança dos dados.
    // ─────────────────────────────────────────────────────────────────────────

    var ICON_ALERTA =
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"' +
        ' fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"' +
        ' stroke-linejoin="round" aria-hidden="true">' +
        '<circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>';

    // Regras espelham LoginRequest.php — alterações lá devem ser refletidas aqui
    var REGRAS = {
        email: function (v) {
            if (!v.trim())                              return 'O e-mail é obrigatório.';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) return 'Informe um e-mail válido.';
            return null;
        },
        password: function (v) {
            if (!v)          return 'A senha é obrigatória.';
            if (v.length < 8) return 'A senha deve ter no mínimo 8 caracteres.';
            return null;
        },
    };

    // Insere ou remove a mensagem de erro abaixo do campo
    function mostrarErro(campo, msg) {
        var wrapper = campo.closest('.pc-field');
        if (!wrapper) return;
        wrapper.querySelectorAll('.pc-field-error--js').forEach(function (el) { el.remove(); });
        if (msg) {
            campo.classList.add('pc-input--error');
            var div = document.createElement('div');
            div.className   = 'pc-field-error pc-field-error--js';
            div.setAttribute('role', 'alert');
            div.innerHTML   = ICON_ALERTA + '<span>' + msg + '</span>';
            var help = wrapper.querySelector('.pc-field-help');
            help ? wrapper.insertBefore(div, help) : wrapper.appendChild(div);
        } else {
            campo.classList.remove('pc-input--error');
        }
    }

    function validar(campo) {
        var regra = REGRAS[campo.name];
        if (!regra) return true;
        var erro = regra(campo.value);
        mostrarErro(campo, erro);
        return erro === null;
    }

    var form = document.getElementById('form-login');
    if (!form) return;

    // Valida ao sair do campo; re-valida em tempo real se já há erro visível
    Object.keys(REGRAS).forEach(function (nome) {
        var campo = form.querySelector('[name="' + nome + '"]');
        if (!campo) return;
        campo.addEventListener('blur', function () { validar(campo); });
        campo.addEventListener('input', function () {
            if (campo.classList.contains('pc-input--error')) validar(campo);
        });
    });

    // Bloqueia envio se algum campo for inválido
    form.addEventListener('submit', function (e) {
        var ok = true;
        Object.keys(REGRAS).forEach(function (nome) {
            var campo = form.querySelector('[name="' + nome + '"]');
            if (campo && !validar(campo)) ok = false;
        });
        if (!ok) {
            e.preventDefault();
            var primeiro = form.querySelector('.pc-field-error--js');
            if (primeiro) primeiro.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Toggle de visibilidade da senha
    var btnToggle = document.getElementById('toggle-pwd');
    var inputPwd  = document.getElementById('password');
    if (btnToggle && inputPwd) {
        btnToggle.addEventListener('click', function () {
            var oculto = inputPwd.type === 'password';
            inputPwd.type = oculto ? 'text' : 'password';
            btnToggle.querySelector('.icon-show').style.display = oculto ? 'none' : '';
            btnToggle.querySelector('.icon-hide').style.display = oculto ? ''     : 'none';
            btnToggle.setAttribute('aria-label', oculto ? 'Ocultar senha' : 'Mostrar senha');
        });
    }

})();
</script>
@endpush
