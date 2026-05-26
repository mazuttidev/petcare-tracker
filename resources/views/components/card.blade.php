{{--
    Card base.
    Uso:
        <x-card>conteúdo</x-card>
        <x-card :pad="false">conteúdo sem padding interno</x-card>
        <x-card class="overflow-hidden">…</x-card>
--}}
@props(['pad' => true])

<div {{ $attributes->merge(['class' => 'pc-card' . ($pad ? ' pc-card-pad' : '')]) }}>
    {{ $slot }}
</div>
