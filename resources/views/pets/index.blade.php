@extends('layouts.app', ['activeNav' => 'pets', 'title' => 'Meus Pets — PetCare'])

@section('content')

<x-page-header title="Meus Pets" subtitle="{{ $pets->count() }} pet{{ $pets->count() !== 1 ? 's' : '' }} cadastrado{{ $pets->count() !== 1 ? 's' : '' }}">
    <x-slot name="actions">
        <x-btn icon="plus" href="{{ route('pets.create') }}">Novo pet</x-btn>
    </x-slot>
</x-page-header>

@if($pets->isEmpty())
    {{-- Estado vazio --}}
    <x-card style="text-align:center;padding:56px 20px">
        <div style="width:64px;height:64px;border-radius:32px;background:var(--pc-primary-100);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px">
            <x-icon name="paw" size="32" color="var(--pc-primary-700)" />
        </div>
        <div class="pc-h3">Nenhum pet cadastrado ainda</div>
        <div class="pc-small" style="margin-top:6px;max-width:340px;margin-inline:auto">
            Cadastre o primeiro pet para começar a registrar vacinas, pesagens e consultas.
        </div>
        <div style="margin-top:20px">
            <x-btn icon="plus" href="{{ route('pets.create') }}">Cadastrar primeiro pet</x-btn>
        </div>
    </x-card>

@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px">
        @foreach($pets as $pet)
        <x-card :pad="false" style="overflow:hidden">

            {{-- Faixa de cor / foto do pet --}}
            <div class="pc-photo-placeholder" style="height:96px;font-size:22px;font-weight:700;
                background:linear-gradient(135deg,
                    {{ match($pet->especie) {
                        'Cão'    => 'var(--pc-primary-200), var(--pc-primary-400)',
                        'Gato'   => '#E0D4F5, #B794E8',
                        'Ave'    => '#BFEFFF, #5DC8F0',
                        'Peixe'  => '#C3E8FF, #3AA0D5',
                        default  => 'var(--pc-n-100), var(--pc-n-300)',
                    } }})">
                {{ strtoupper(substr($pet->nome, 0, 1)) }}
            </div>

            <div style="padding:16px">
                {{-- Nome e status --}}
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px">
                    <div>
                        <div class="pc-h3">{{ $pet->nome }}</div>
                        <div class="pc-small" style="margin-top:2px">
                            {{ $pet->especie }}{{ $pet->raca ? ' · ' . $pet->raca : '' }}
                            · {{ $pet->sexo }}
                        </div>
                    </div>
                    @php
                    $badgeVariant = match($pet->status) {
                        'Ativo'    => 'success',
                        'Falecido' => 'neutral',
                        'Doado'    => 'info',
                        'Perdido'  => 'danger',
                        default    => 'neutral',
                    };
                    @endphp
                    <x-badge :variant="$badgeVariant" dot>{{ $pet->status }}</x-badge>
                </div>

                {{-- Peso e idade --}}
                <div style="display:flex;gap:16px;margin-top:12px">
                    @if($pet->peso_atual)
                    <div>
                        <div class="pc-caption">Peso</div>
                        <div class="pc-body-strong pc-mono" style="font-size:14px;margin-top:2px">
                            {{ number_format($pet->peso_atual, 2, ',', '') }} kg
                        </div>
                    </div>
                    @endif
                    @if($pet->data_nascimento)
                    <div>
                        <div class="pc-caption">Idade</div>
                        <div class="pc-body-strong" style="font-size:14px;margin-top:2px">
                            {{ $pet->data_nascimento->diffForHumans(null, true) }}
                        </div>
                    </div>
                    @endif
                    @if($pet->castrado)
                    <div style="display:flex;align-items:flex-end;padding-bottom:1px">
                        <x-badge variant="info">Castrado</x-badge>
                    </div>
                    @endif
                </div>

                {{-- Ações --}}
                <div style="display:flex;gap:8px;margin-top:16px;padding-top:14px;border-top:1px solid var(--pc-n-100)">
                    <x-btn size="sm" variant="secondary" icon="eye" href="{{ route('pets.show', $pet) }}">
                        Ver perfil
                    </x-btn>
                    <x-btn size="sm" variant="ghost" icon="edit" href="{{ route('pets.edit', $pet) }}">
                        Editar
                    </x-btn>
                    <div style="flex:1"></div>

                    {{--
                        data-confirmar-exclusao: seletor para o listener de eventos abaixo.
                        data-nome / data-url: passados via {{ }} — Blade escapa para HTML entities,
                        que o JS recebe pelo .dataset sem risco de XSS.
                    --}}
                    <button
                        type="button"
                        class="pc-btn pc-btn-ghost pc-btn-icon pc-btn-sm"
                        aria-label="Remover {{ $pet->nome }}"
                        style="color:var(--pc-danger-500)"
                        data-confirmar-exclusao
                        data-nome="{{ $pet->nome }}"
                        data-url="{{ route('pets.destroy', $pet) }}"
                    >
                        <x-icon name="trash" size="16" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </x-card>
        @endforeach
    </div>
@endif

{{--
    Formulário oculto compartilhado por todos os cards.
    A action é preenchida pelo JS (abrirExclusao) antes de o modal ser aberto,
    garantindo que o DELETE chegue ao pet correto.
--}}
<form id="form-excluir-pet" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

@push('modals')
<x-modal id="modal-excluir-pet" size="sm" title="Remover pet?">

    <p class="pc-body" style="color:var(--pc-n-700)">
        Tem certeza que deseja remover
        <strong id="modal-excluir-nome" style="color:var(--pc-n-900)"></strong>?
    </p>
    <p class="pc-small" style="color:var(--pc-n-500);margin-top:8px">
        O registro vai para a lixeira e pode ser restaurado se necessário.
    </p>

    <x-slot name="footer">
        <x-btn variant="secondary" onclick="pcCloseModal('modal-excluir-pet')">Cancelar</x-btn>
        {{-- form="..." associa este botão ao formulário oculto fora do modal (HTML5) --}}
        <x-btn variant="danger" icon="trash" type="submit" form="form-excluir-pet">
            Sim, remover
        </x-btn>
    </x-slot>

</x-modal>
@endpush

@push('scripts')
<script>
// Delegação de eventos: um único listener para todos os botões de exclusão da lista
document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-confirmar-exclusao]');
    if (!btn) return;

    // Atualiza o form oculto com a URL correta antes de abrir o modal
    document.getElementById('form-excluir-pet').action = btn.dataset.url;
    document.getElementById('modal-excluir-nome').textContent = '"' + btn.dataset.nome + '"';
    pcOpenModal('modal-excluir-pet');
});
</script>
@endpush

@endsection
