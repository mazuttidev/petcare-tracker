<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name', 'PetCare Tracker') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

    @stack('styles')
</head>
<body>

<div class="pc-auth">

    {{-- ── Painel esquerdo — decorativo ──────────────────── --}}
    <div class="pc-auth-side">

        {{-- Marca --}}
        <div class="pc-auth-side-brand">
            <div class="pc-auth-side-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                    <circle cx="6"  cy="10" r="2"/>
                    <circle cx="18" cy="10" r="2"/>
                    <circle cx="9"  cy="6"  r="2"/>
                    <circle cx="15" cy="6"  r="2"/>
                    <path d="M12 13c-3.5 0-6 2.5-6 5 0 1.5 1 2.5 2.5 2.5 1 0 1.5-.5 3.5-.5s2.5.5 3.5.5c1.5 0 2.5-1 2.5-2.5 0-2.5-2.5-5-6-5z"/>
                </svg>
            </div>
            <div>
                <div style="font-weight:700;font-size:17px;line-height:1.2">PetCare</div>
                <div style="font-size:11px;opacity:.7;letter-spacing:.06em;text-transform:uppercase;margin-top:1px">Tracker</div>
            </div>
        </div>

        {{-- Ilustração + tagline --}}
        <div class="pc-auth-side-center">
            <svg class="pc-auth-side-paw" width="210" height="210" viewBox="0 0 24 24" fill="white" stroke="none">
                <circle cx="6"  cy="10" r="1.8"/>
                <circle cx="18" cy="10" r="1.8"/>
                <circle cx="9"  cy="6"  r="1.8"/>
                <circle cx="15" cy="6"  r="1.8"/>
                <path d="M12 13c-3.5 0-6 2.5-6 5 0 1.5 1 2.5 2.5 2.5 1 0 1.5-.5 3.5-.5s2.5.5 3.5.5c1.5 0 2.5-1 2.5-2.5 0-2.5-2.5-5-6-5z"/>
            </svg>
            <div>
                <div style="font-size:22px;font-weight:700;line-height:1.3;letter-spacing:-.015em">
                    Cuide bem de quem<br>te faz feliz
                </div>
                <div style="font-size:14px;opacity:.75;margin-top:8px;line-height:1.5">
                    Saúde, peso e rotina dos seus pets<br>em um só lugar.
                </div>
            </div>
        </div>

        {{-- Destaques de funcionalidades --}}
        <div class="pc-auth-feats">
            <div class="pc-auth-feat">
                <div class="pc-auth-feat-ico">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 3v6a4 4 0 008 0V3"/><path d="M5 3h2M11 3h2"/>
                        <path d="M9 13v2a5 5 0 0010 0v-2"/><circle cx="19" cy="13" r="2"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600">Saúde em dia</div>
                    <div style="font-size:12px;opacity:.7;margin-top:1px">Vacinas, consultas e medicações</div>
                </div>
            </div>
            <div class="pc-auth-feat">
                <div class="pc-auth-feat-ico">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600">Controle de peso</div>
                    <div style="font-size:12px;opacity:.7;margin-top:1px">Gráfico de evolução com alertas</div>
                </div>
            </div>
            <div class="pc-auth-feat">
                <div class="pc-auth-feat-ico">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white" stroke="none">
                        <circle cx="6"  cy="10" r="1.6"/>
                        <circle cx="18" cy="10" r="1.6"/>
                        <circle cx="9"  cy="6"  r="1.6"/>
                        <circle cx="15" cy="6"  r="1.6"/>
                        <path d="M12 13c-3.5 0-6 2.5-6 5 0 1.5 1 2.5 2.5 2.5 1 0 1.5-.5 3.5-.5s2.5.5 3.5.5c1.5 0 2.5-1 2.5-2.5 0-2.5-2.5-5-6-5z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600">Múltiplos pets</div>
                    <div style="font-size:12px;opacity:.7;margin-top:1px">Toda a família animal reunida</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Painel direito — formulário ────────────────────── --}}
    <div class="pc-auth-main">
        <div class="pc-auth-box">

            {{-- Logo compacta (só mobile) --}}
            <div class="pc-auth-mobile-logo">
                <div class="pc-auth-mobile-logo-mark">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff">
                        <circle cx="6"  cy="10" r="2"/>
                        <circle cx="18" cy="10" r="2"/>
                        <circle cx="9"  cy="6"  r="2"/>
                        <circle cx="15" cy="6"  r="2"/>
                        <path d="M12 13c-3.5 0-6 2.5-6 5 0 1.5 1 2.5 2.5 2.5 1 0 1.5-.5 3.5-.5s2.5.5 3.5.5c1.5 0 2.5-1 2.5-2.5 0-2.5-2.5-5-6-5z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:16px;color:var(--pc-n-900)">PetCare</div>
                    <div style="font-size:11px;color:var(--pc-n-500);letter-spacing:.05em;text-transform:uppercase">Tracker</div>
                </div>
            </div>

            @yield('content')

        </div>
    </div>

</div>

@stack('scripts')
</body>
</html>
