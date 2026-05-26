@extends('layouts.auth', ['title' => 'Criar conta — PetCare Tracker'])

@section('content')

{{-- Cabeçalho da tela --}}
<div style="margin-bottom:28px">
    <div class="pc-h2" style="color:var(--pc-n-900);margin-bottom:6px">Criar conta grátis</div>
    <p class="pc-body" style="color:var(--pc-n-500)">
        Cadastre-se e comece a cuidar dos seus pets hoje mesmo.
    </p>
</div>

{{-- Alertas de erro --}}
@if($errors->any())
    <x-alert variant="danger" style="margin-bottom:20px">
        Corrija os erros abaixo para continuar.
    </x-alert>
@endif
@if(session('error'))
    <x-alert variant="danger" style="margin-bottom:20px">
        {{ session('error') }}
    </x-alert>
@endif

<form method="POST" action="{{ route('register') }}" novalidate>
    @csrf

    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Nome --}}
        <x-form.input
            name="name"
            label="Nome completo"
            placeholder="Como você se chama"
            :error="$errors->first('name')"
            autocomplete="name"
        />

        {{-- E-mail --}}
        <x-form.input
            name="email"
            type="email"
            label="E-mail"
            placeholder="seu@email.com"
            :error="$errors->first('email')"
            autocomplete="email"
        />

        {{-- Senha com toggle --}}
        <div class="pc-field">
            <label class="pc-field-label" for="password">Senha</label>
            <div class="pc-input-wrap">
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="pc-input{{ $errors->has('password') ? ' pc-input--error' : '' }}"
                    placeholder="Mínimo 8 caracteres"
                    autocomplete="new-password"
                />
                <button type="button" class="pc-pwd-toggle" id="toggle-pwd" aria-label="Mostrar senha">
                    <svg class="icon-show" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
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

        {{-- Confirmar senha com toggle --}}
        <div class="pc-field">
            <label class="pc-field-label" for="password_confirmation">Confirmar senha</label>
            <div class="pc-input-wrap">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="pc-input{{ $errors->has('password_confirmation') ? ' pc-input--error' : '' }}"
                    placeholder="Repita a senha"
                    autocomplete="new-password"
                />
                <button type="button" class="pc-pwd-toggle" id="toggle-pwd-confirm" aria-label="Mostrar confirmação de senha">
                    <svg class="icon-show" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="icon-hide" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                         style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <div class="pc-field-error" role="alert">
                    <x-icon name="alert_circle" size="13" aria-hidden="true" />
                    {{ $message }}
                </div>
            @enderror
        </div>

    </div>

    <x-btn type="submit" style="width:100%;margin-top:24px" size="lg">
        Criar conta
    </x-btn>

</form>

<p class="pc-body" style="text-align:center;margin-top:24px;color:var(--pc-n-500)">
    Já tem uma conta?
    <a href="{{ route('login') }}" class="pc-link">Entrar</a>
</p>

@endsection

@push('scripts')
<script>
(function () {
    function initToggle(btnId, inputId) {
        var btn   = document.getElementById(btnId);
        var input = document.getElementById(inputId);
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            var isHidden = input.type === 'password';
            input.type   = isHidden ? 'text' : 'password';
            btn.querySelector('.icon-show').style.display = isHidden ? 'none' : '';
            btn.querySelector('.icon-hide').style.display = isHidden ? ''     : 'none';
            btn.setAttribute('aria-label', isHidden ? 'Ocultar senha' : 'Mostrar senha');
        });
    }
    initToggle('toggle-pwd',         'password');
    initToggle('toggle-pwd-confirm', 'password_confirmation');
})();
</script>
@endpush
