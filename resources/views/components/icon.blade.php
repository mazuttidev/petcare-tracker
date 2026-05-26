{{--
    Ícones SVG do PetCare Tracker — linha 24×24, cantos arredondados.
    Uso: <x-icon name="paw" size="20" color="var(--pc-primary-700)" />
    Nota: {!! $path !!} é seguro aqui porque $path vem de um mapa
    interno hardcoded, não de dado do usuário.
--}}
@props(['name', 'size' => 20, 'color' => 'currentColor', 'stroke' => '1.75'])

@php
$icons = [
    'home'         => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
    'paw'          => '<circle cx="6" cy="10" r="1.8"/><circle cx="18" cy="10" r="1.8"/><circle cx="9" cy="6" r="1.8"/><circle cx="15" cy="6" r="1.8"/><path d="M12 13c-3.5 0-6 2.5-6 5 0 1.5 1 2.5 2.5 2.5 1 0 1.5-.5 3.5-.5s2.5.5 3.5.5c1.5 0 2.5-1 2.5-2.5 0-2.5-2.5-5-6-5z"/>',
    'calendar'     => '<rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M3.5 10h17"/><path d="M8 3v4M16 3v4"/>',
    'syringe'      => '<path d="M14 4l6 6"/><path d="M13 11l-7 7-3 1 1-3 7-7"/><path d="M11 9l4 4"/><path d="M16 6l2 2"/>',
    'stethoscope'  => '<path d="M5 3v6a4 4 0 008 0V3"/><path d="M5 3h2M11 3h2"/><path d="M9 13v2a5 5 0 0010 0v-2"/><circle cx="19" cy="13" r="2"/>',
    'pill'         => '<rect x="3" y="9" width="18" height="6" rx="3" transform="rotate(-45 12 12)"/><path d="M8.5 8.5l7 7"/>',
    'bowl'         => '<path d="M3 11h18"/><path d="M4 11c0 5 3.5 8 8 8s8-3 8-8"/><path d="M9 7c0-1.5 1.5-3 3-3s3 1.5 3 3"/>',
    'scale'        => '<rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><path d="M8 9l4 4 5-6"/><circle cx="12" cy="16" r="1"/>',
    'bell'         => '<path d="M6 16V11a6 6 0 0112 0v5l1.5 2h-15L6 16z"/><path d="M10 20a2 2 0 004 0"/>',
    'user'         => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>',
    'settings'     => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>',
    'plus'         => '<path d="M12 5v14M5 12h14"/>',
    'minus'        => '<path d="M5 12h14"/>',
    'check'        => '<path d="M5 12l4 4 10-10"/>',
    'x'            => '<path d="M6 6l12 12M18 6L6 18"/>',
    'chevronDown'  => '<path d="M6 9l6 6 6-6"/>',
    'chevronRight' => '<path d="M9 6l6 6-6 6"/>',
    'chevronLeft'  => '<path d="M15 6l-6 6 6 6"/>',
    'search'       => '<circle cx="11" cy="11" r="7"/><path d="M16 16l4.5 4.5"/>',
    'filter'       => '<path d="M3 5h18l-7 9v6l-4-2v-4L3 5z"/>',
    'more'         => '<circle cx="6" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="18" cy="12" r="1.5"/>',
    'upload'       => '<path d="M12 16V4"/><path d="M6 10l6-6 6 6"/><path d="M4 18v2a1 1 0 001 1h14a1 1 0 001-1v-2"/>',
    'download'     => '<path d="M12 4v12"/><path d="M6 10l6 6 6-6"/><path d="M4 20h16"/>',
    'edit'         => '<path d="M3 21l4-1 11-11-3-3L4 17l-1 4z"/><path d="M14 6l3 3"/>',
    'trash'        => '<path d="M4 7h16"/><path d="M9 7V4h6v3"/><path d="M6 7l1 13a2 2 0 002 2h6a2 2 0 002-2l1-13"/>',
    'info'         => '<circle cx="12" cy="12" r="9"/><path d="M12 8h.01"/><path d="M11 12h1v5h1"/>',
    'warning'      => '<path d="M12 3l10 17H2L12 3z"/><path d="M12 10v4"/><path d="M12 17h.01"/>',
    'check_circle' => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
    'alert_circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
    'heart'        => '<path d="M12 20s-7-4.5-9.5-9C1 8.5 3 5 6.5 5c2 0 3.5 1 5 3 1.5-2 3-3 5-3C20 5 22 8.5 20.5 11c-2.5 4.5-8.5 9-8.5 9z"/>',
    'sparkle'      => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4"/><path d="M6 6l3 3M18 18l-3-3M6 18l3-3M18 6l-3 3"/>',
    'weight'       => '<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M9 5c0-1.5 1.5-2 3-2s3 .5 3 2"/><path d="M9 12l3-4 3 6"/>',
    'camera'       => '<rect x="3" y="7" width="18" height="13" rx="2.5"/><path d="M8 7l1.5-3h5L16 7"/><circle cx="12" cy="13.5" r="3.5"/>',
    'logout'       => '<path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>',
    'mail'         => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 7 9-7"/>',
    'lock'         => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/>',
    'eye'          => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
    'clipboard'    => '<rect x="6" y="5" width="12" height="16" rx="2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/>',
    'arrow_left'   => '<path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/>',
    'arrow_right'  => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
    'trending_up'  => '<path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/>',
    'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
];
$path = $icons[$name] ?? '';
@endphp

<svg
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 24 24"
    fill="none"
    stroke="{{ $color }}"
    stroke-width="{{ $stroke }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    style="flex-shrink:0"
    {{ $attributes }}
>{!! $path !!}</svg>
