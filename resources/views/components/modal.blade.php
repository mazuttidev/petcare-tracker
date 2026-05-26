{{--
    Modal com overlay.
    Uso:
        <!-- Botão de abertura: -->
        <x-btn onclick="pcOpenModal('modal-exemplo')">Abrir</x-btn>

        <!-- Componente modal: -->
        <x-modal id="modal-exemplo" title="Título do modal">
            <p>Conteúdo do modal</p>
            <x-slot name="footer">
                <x-btn variant="secondary" onclick="pcCloseModal('modal-exemplo')">Cancelar</x-btn>
                <x-btn variant="primary" type="submit">Confirmar</x-btn>
            </x-slot>
        </x-modal>
    Tamanhos: sm | md (padrão) | lg
--}}
@props(['id', 'title' => null, 'subtitle' => null, 'size' => 'md'])

@php
$dialogClass = 'pc-modal-dialog' . ($size !== 'md' ? ' pc-modal-dialog-' . $size : '');
$titleId = $id . '-title';
@endphp

<div
    id="{{ $id }}"
    class="pc-modal-overlay"
    aria-hidden="true"
    onclick="if(event.target===this)pcCloseModal('{{ $id }}')"
>
    <div class="{{ $dialogClass }}"
         role="dialog"
         aria-modal="true"
         @if($title) aria-labelledby="{{ $titleId }}" @endif>
        @if($title)
        <div class="pc-modal-header">
            <div>
                <div id="{{ $titleId }}" class="pc-h3">{{ $title }}</div>
                @if($subtitle)
                    <div class="pc-small" style="margin-top:4px">{{ $subtitle }}</div>
                @endif
            </div>
            <button
                class="pc-btn pc-btn-icon pc-btn-ghost"
                style="height:32px;width:32px;flex-shrink:0"
                onclick="pcCloseModal('{{ $id }}')"
                type="button"
                aria-label="Fechar"
            >
                <x-icon name="x" size="18" aria-hidden="true" />
            </button>
        </div>
        @endif

        <div class="pc-modal-body">
            {{ $slot }}
        </div>

        @isset($footer)
        <div class="pc-modal-footer">
            {{ $footer }}
        </div>
        @endisset
    </div>
</div>
