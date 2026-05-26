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

<form method="POST" action="{{ route('login') }}" novalidate>
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
    var btn   = document.getElementById('toggle-pwd');
    var input = document.getElementById('password');
    if (!btn || !input) return;
    btn.addEventListener('click', function () {
        var isHidden = input.type === 'password';
        input.type   = isHidden ? 'text' : 'password';
        btn.querySelector('.icon-show').style.display = isHidden ? 'none' : '';
        btn.querySelector('.icon-hide').style.display = isHidden ? ''     : 'none';
        btn.setAttribute('aria-label', isHidden ? 'Ocultar senha' : 'Mostrar senha');
    });
})();
</script>
@endpush
