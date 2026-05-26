{{--
    Cabeçalho de página com título, subtítulo e slot de ações.
    Uso:
        <x-page-header title="Meus Pets" subtitle="4 pets cadastrados">
            <x-slot name="actions">
                <x-btn icon="plus">Novo pet</x-btn>
            </x-slot>
        </x-page-header>
--}}
@props(['title', 'subtitle' => null])

<div class="pc-page-header">
    <div>
        <h1 class="pc-h1">{{ $title }}</h1>
        @if($subtitle)
            <p class="pc-body pc-muted" style="margin-top:4px">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="pc-page-header-actions">{{ $actions }}</div>
    @endisset
</div>
