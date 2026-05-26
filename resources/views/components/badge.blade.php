{{--
    Badge de status.
    Uso:
        <x-badge variant="success" dot>Ativo</x-badge>
        <x-badge variant="warning">Vence em 12d</x-badge>
        <x-badge variant="danger" dot>Perdido</x-badge>
    Variantes: success | warning | danger | info | neutral
--}}
@props(['variant' => 'neutral', 'dot' => false])

<span {{ $attributes->merge(['class' => 'pc-badge pc-badge-' . $variant]) }}>
    @if($dot)<span class="pc-dot"></span>@endif
    {{ $slot }}
</span>
