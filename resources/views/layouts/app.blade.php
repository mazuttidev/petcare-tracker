<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name', 'PetCare Tracker') }}</title>

    {{-- Google Fonts: Plus Jakarta Sans + JetBrains Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />

    {{-- CSS do design system (servido via public/css — sem build step necessário) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

    @stack('styles')
</head>
<body>

<div class="pc-layout">

    {{-- ── Sidebar ────────────────────────────────────────────── --}}
    <aside class="pc-sidebar" id="pc-sidebar">

        {{-- Logo e nome --}}
        <div class="pc-sidebar-brand">
            <div class="pc-logo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff">
                    <circle cx="6"  cy="10" r="2"/>
                    <circle cx="18" cy="10" r="2"/>
                    <circle cx="9"  cy="6"  r="2"/>
                    <circle cx="15" cy="6"  r="2"/>
                    <path d="M12 13c-3.5 0-6 2.5-6 5 0 1.5 1 2.5 2.5 2.5 1 0 1.5-.5 3.5-.5s2.5.5 3.5.5c1.5 0 2.5-1 2.5-2.5 0-2.5-2.5-5-6-5z"/>
                </svg>
            </div>
            <div>
                <div style="font-weight:700;font-size:15px;color:var(--pc-n-900);line-height:1.2">PetCare</div>
                <div class="pc-caption" style="margin-top:1px">Tracker</div>
            </div>
        </div>

        {{-- Itens de navegação --}}
        <div class="pc-sidebar-body">
            <div class="pc-caption pc-nav-section">Principal</div>
            <nav class="pc-nav">
                <a href="{{ route('dashboard') }}"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'home') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="home" size="18" />
                    Dashboard
                </a>
                <a href="{{ route('pets.index') }}"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'pets') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="paw" size="18" />
                    Meus pets
                </a>
            </nav>

            <div class="pc-caption pc-nav-section">Saúde</div>
            <nav class="pc-nav">
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'vacinas') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="syringe" size="18" />
                    Vacinas
                </a>
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'consultas') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="stethoscope" size="18" />
                    Consultas
                </a>
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'medicacoes') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="pill" size="18" />
                    Medicações
                </a>
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'alimentacao') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="bowl" size="18" />
                    Alimentação
                </a>
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'pesagens') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="scale" size="18" />
                    Pesagens
                </a>
            </nav>

            {{-- Empurra os itens de conta para o final --}}
            <div class="pc-spacer"></div>

            <nav class="pc-nav" style="margin-bottom:8px">
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'perfil') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="user" size="18" />
                    Perfil
                </a>
                <a href="#"
                   class="pc-nav-item {{ (isset($activeNav) && $activeNav === 'configuracoes') ? 'pc-nav-item--active' : '' }}">
                    <x-icon name="settings" size="18" />
                    Configurações
                </a>
            </nav>
        </div>

        {{-- Rodapé do usuário --}}
        <div class="pc-sidebar-user">
            <div class="pc-avatar" style="width:32px;height:32px;font-size:13px">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:var(--pc-n-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ Auth::user()->name ?? 'Usuário' }}
                </div>
                <div class="pc-mono" style="font-size:10.5px;color:var(--pc-n-500);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ Auth::user()->email ?? '' }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="pc-btn pc-btn-ghost pc-btn-icon pc-btn-sm"
                        aria-label="Sair"
                        title="Sair">
                    <x-icon name="logout" size="16" color="var(--pc-n-500)" />
                </button>
            </form>
        </div>

    </aside>

    {{-- Overlay para fechar sidebar no mobile --}}
    <div class="pc-sidebar-overlay" id="pc-sidebar-overlay" onclick="pcToggleSidebar()"></div>

    {{-- ── Coluna principal ────────────────────────────────────── --}}
    <main class="pc-main">

        {{-- Topbar --}}
        <header class="pc-topbar">

            {{-- Hamburger (mobile) --}}
            <button class="pc-btn pc-btn-ghost pc-btn-icon pc-hamburger" onclick="pcToggleSidebar()" type="button" aria-label="Abrir menu">
                <x-icon name="more" size="20" />
            </button>

            {{-- Campo de busca --}}
            <div class="pc-topbar-search">
                <span class="pc-topbar-search-icon">
                    <x-icon name="search" size="16" color="var(--pc-n-400)" />
                </span>
                <input
                    type="search"
                    class="pc-input"
                    placeholder="Buscar pet, vacina, lembrete…"
                    autocomplete="off"
                />
            </div>

            <div class="pc-topbar-spacer"></div>

            {{-- Notificações --}}
            <div class="pc-notif-btn">
                <button class="pc-btn pc-btn-secondary pc-btn-icon" type="button" aria-label="Notificações">
                    <x-icon name="bell" size="18" />
                </button>
                <span class="pc-notif-dot"></span>
            </div>

            {{-- Ação principal da página (slot opcional) --}}
            @isset($topbarAction)
                {{ $topbarAction }}
            @else
                <x-btn icon="plus" href="#">Novo pet</x-btn>
            @endisset

        </header>

        {{-- Área de conteúdo --}}
        <div class="pc-page">

            {{-- Flash messages --}}
            @if(session('success'))
                <x-alert variant="success" style="margin-bottom:20px">{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert variant="danger" style="margin-bottom:20px">{{ session('error') }}</x-alert>
            @endif
            @if($errors->any())
                <x-alert variant="danger" title="Corrija os erros abaixo:" style="margin-bottom:20px">
                    <ul style="margin-top:4px;padding-left:16px">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            @yield('content')
        </div>

    </main>
</div>

{{-- Modais globais --}}
@stack('modals')

{{-- JS: helpers de modal e sidebar mobile --}}
<script>
var _pcPreviousFocus = null;

function pcOpenModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    _pcPreviousFocus = document.activeElement;
    el.removeAttribute('aria-hidden');
    el.classList.add('pc-modal--open');
    var focusable = el.querySelectorAll(
        'button:not([disabled]),[href],input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])'
    );
    if (focusable.length) focusable[0].focus();
}

function pcCloseModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.setAttribute('aria-hidden', 'true');
    el.classList.remove('pc-modal--open');
    if (_pcPreviousFocus) { _pcPreviousFocus.focus(); _pcPreviousFocus = null; }
}

document.addEventListener('keydown', function(e) {
    /* Esc fecha o modal aberto */
    if (e.key === 'Escape') {
        var open = document.querySelector('.pc-modal-overlay.pc-modal--open');
        if (open) { pcCloseModal(open.id); }
        return;
    }
    /* Tab fica preso dentro do modal */
    if (e.key !== 'Tab') return;
    var modal = document.querySelector('.pc-modal-overlay.pc-modal--open');
    if (!modal) return;
    var focusable = Array.from(modal.querySelectorAll(
        'button:not([disabled]),[href],input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])'
    ));
    if (!focusable.length) return;
    var first = focusable[0], last = focusable[focusable.length - 1];
    if (e.shiftKey) {
        if (document.activeElement === first) { e.preventDefault(); last.focus(); }
    } else {
        if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
    }
});

function pcToggleSidebar() {
    var sidebar = document.getElementById('pc-sidebar');
    var overlay = document.getElementById('pc-sidebar-overlay');
    sidebar.classList.toggle('pc-sidebar--open');
    overlay.classList.toggle('pc-sidebar--open');
}
</script>

@stack('scripts')
</body>
</html>
