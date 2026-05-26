{{--
    Alerta com ícone automático por variante.
    Uso:
        <x-alert variant="success" title="Salvo!">Pet cadastrado com sucesso.</x-alert>
        <x-alert variant="danger">Não foi possível salvar. Tente novamente.</x-alert>
    Variantes: success | warning | danger | info
--}}
@props(['variant' => 'info', 'title' => null])

@php
$iconMap = [
    'success' => 'check_circle',
    'warning' => 'warning',
    'danger'  => 'alert_circle',
    'info'    => 'info',
];
$icon = $iconMap[$variant] ?? 'info';
@endphp

<div {{ $attributes->merge(['class' => 'pc-alert pc-alert-' . $variant]) }}>
    <x-icon :name="$icon" size="20" />
    <div>
        @if($title)
            <div class="pc-alert-title">{{ $title }}</div>
        @endif
        <div class="pc-alert-body">{{ $slot }}</div>
    </div>
</div>
