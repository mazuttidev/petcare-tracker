{{--
    Botão reutilizável do PetCare.
    Uso:
        <x-btn>Salvar</x-btn>
        <x-btn variant="secondary" size="sm">Cancelar</x-btn>
        <x-btn variant="danger" icon="trash">Apagar</x-btn>
        <x-btn href="{{ route('pets.index') }}" variant="ghost">Ver tudo</x-btn>
        <x-btn type="submit" :loading="true">Salvando…</x-btn>
--}}
@props([
    'variant'   => 'primary',
    'size'      => 'md',
    'icon'      => null,
    'iconColor' => null,
    'loading'   => false,
    'href'      => null,
    'type'      => 'button',
])

@php
$cls = 'pc-btn pc-btn-' . $variant;
if ($size === 'sm') $cls .= ' pc-btn-sm';
if ($size === 'lg') $cls .= ' pc-btn-lg';

$autoIconColor = $iconColor ?? (in_array($variant, ['primary', 'danger']) ? '#fff' : 'currentColor');
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>
        @if($icon)<x-icon :name="$icon" size="16" :color="$autoIconColor" />@endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $cls]) }}
        @if($loading) disabled @endif
    >
        @if($loading)
            <span class="pc-spinner"></span>
        @elseif($icon)
            <x-icon :name="$icon" size="16" :color="$autoIconColor" />
        @endif
        {{ $slot }}
    </button>
@endif
