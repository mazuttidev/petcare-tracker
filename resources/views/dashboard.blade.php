@extends('layouts.app', ['activeNav' => 'home', 'title' => 'Dashboard — PetCare'])

@section('content')

<x-page-header title="Dashboard" subtitle="Visão geral da sua família de pets.">
    <x-slot name="actions">
        <x-btn icon="plus" href="{{ route('pets.create') }}">Novo pet</x-btn>
    </x-slot>
</x-page-header>

{{-- ─── Três cartões-resumo ─────────────────────────────────────────────── --}}

<div class="pc-grid-3">

    {{-- ── Cartão 1: Pets ativos ──────────────────────────────────────── --}}
    <x-card>

        <div style="display:flex;align-items:flex-start;gap:16px">
            {{-- Ícone --}}
            <div style="width:48px;height:48px;border-radius:50%;flex-shrink:0;
                        background:var(--pc-primary-100);
                        display:flex;align-items:center;justify-content:center">
                <x-icon name="paw" size="24" color="var(--pc-primary-700)" />
            </div>

            {{-- Métrica --}}
            <div style="flex:1;min-width:0">
                <div style="font-size:36px;font-weight:700;line-height:1;
                            color:var(--pc-n-900);letter-spacing:-.02em">
                    {{ $totalAtivos }}
                </div>
                <div class="pc-body-strong" style="margin-top:4px">
                    Pet{{ $totalAtivos !== 1 ? 's' : '' }} ativo{{ $totalAtivos !== 1 ? 's' : '' }}
                </div>
                <div class="pc-small" style="margin-top:2px">
                    @if($totalPets === 0)
                        Nenhum cadastrado ainda
                    @elseif($totalAtivos === $totalPets)
                        todos os {{ $totalPets }} cadastrados
                    @else
                        de {{ $totalPets }} cadastrado{{ $totalPets !== 1 ? 's' : '' }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Distribuição por espécie --}}
        @if($contagemPorEspecie->isNotEmpty())
        <div style="margin-top:16px;display:flex;flex-wrap:wrap;gap:6px">
            @foreach($contagemPorEspecie as $especie => $qtd)
            <span style="display:inline-flex;align-items:center;gap:4px;
                         padding:3px 8px;border-radius:var(--pc-r-pill);
                         background:var(--pc-n-100);font-size:12px;color:var(--pc-n-700)">
                {{ $especie }} <strong style="color:var(--pc-n-900)">{{ $qtd }}</strong>
            </span>
            @endforeach
        </div>
        @endif

        <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--pc-n-100)">
            <a href="{{ route('pets.index') }}"
               class="pc-btn pc-btn-ghost pc-btn-sm"
               style="width:100%;justify-content:space-between">
                Ver todos os pets
                <x-icon name="chevronRight" size="14" />
            </a>
        </div>

    </x-card>

    {{-- ── Cartão 2: Último pet cadastrado ────────────────────────────── --}}
    <x-card>

        <div class="pc-caption" style="margin-bottom:14px">Último pet cadastrado</div>

        @if($ultimoPet)
        @php
            $gradiente = match($ultimoPet->especie) {
                'Cão'   => 'linear-gradient(135deg,var(--pc-primary-200),var(--pc-primary-400))',
                'Gato'  => 'linear-gradient(135deg,#E0D4F5,#B794E8)',
                'Ave'   => 'linear-gradient(135deg,#BFEFFF,#5DC8F0)',
                'Peixe' => 'linear-gradient(135deg,#C3E8FF,#3AA0D5)',
                default => 'linear-gradient(135deg,var(--pc-n-100),var(--pc-n-300))',
            };
            $badgeVariant = match($ultimoPet->status) {
                'Ativo'    => 'success',
                'Falecido' => 'neutral',
                'Doado'    => 'info',
                'Perdido'  => 'danger',
                default    => 'neutral',
            };
        @endphp

        <div style="display:flex;align-items:center;gap:14px">
            {{-- Avatar com inicial --}}
            <div style="width:52px;height:52px;border-radius:var(--pc-r-md);flex-shrink:0;
                        background:{{ $gradiente }};
                        display:flex;align-items:center;justify-content:center;
                        font-size:20px;font-weight:700;color:var(--pc-n-0)">
                {{ strtoupper(substr($ultimoPet->nome, 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;gap:8px">
                    <div class="pc-h3" style="font-size:17px;
                                              white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $ultimoPet->nome }}
                    </div>
                    <x-badge :variant="$badgeVariant">{{ $ultimoPet->status }}</x-badge>
                </div>
                <div class="pc-small" style="margin-top:3px">
                    {{ $ultimoPet->especie }}{{ $ultimoPet->raca ? ' · ' . $ultimoPet->raca : '' }}
                    · {{ $ultimoPet->sexo }}
                </div>
                <div class="pc-small" style="margin-top:2px;color:var(--pc-n-400)">
                    Cadastrado {{ $ultimoPet->created_at->diffForHumans() }}
                </div>
            </div>
        </div>

        @if($ultimoPet->peso_atual)
        <div style="margin-top:14px;padding:10px 12px;border-radius:var(--pc-r-sm);
                    background:var(--pc-n-50);display:flex;align-items:center;gap:8px">
            <x-icon name="weight" size="15" color="var(--pc-n-500)" />
            <span class="pc-small">Peso atual</span>
            <span class="pc-mono" style="font-weight:700;font-size:14px;
                                         color:var(--pc-primary-700);margin-left:auto">
                {{ number_format($ultimoPet->peso_atual, 2, ',', '') }} kg
            </span>
        </div>
        @endif

        <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--pc-n-100)">
            <a href="{{ route('pets.show', $ultimoPet) }}"
               class="pc-btn pc-btn-ghost pc-btn-sm"
               style="width:100%;justify-content:space-between">
                Ver perfil de {{ $ultimoPet->nome }}
                <x-icon name="chevronRight" size="14" />
            </a>
        </div>

        @else
        {{-- Estado vazio --}}
        <div style="padding:20px 0;text-align:center">
            <x-icon name="paw" size="32" color="var(--pc-n-300)" />
            <div class="pc-small" style="margin-top:8px">
                Nenhum pet cadastrado ainda.
            </div>
        </div>
        <div style="margin-top:8px;padding-top:14px;border-top:1px solid var(--pc-n-100)">
            <a href="{{ route('pets.create') }}"
               class="pc-btn pc-btn-ghost pc-btn-sm"
               style="width:100%;justify-content:space-between">
                Cadastrar primeiro pet
                <x-icon name="chevronRight" size="14" />
            </a>
        </div>
        @endif

    </x-card>

    {{-- ── Cartão 3: Última pesagem ────────────────────────────────────── --}}
    <x-card>

        <div class="pc-caption" style="margin-bottom:14px">Última pesagem registrada</div>

        @if($ultimaPesagem)

        <div style="display:flex;align-items:flex-start;gap:14px">
            {{-- Ícone de balança --}}
            <div style="width:48px;height:48px;border-radius:50%;flex-shrink:0;
                        background:var(--pc-info-100,#dbeafe);
                        display:flex;align-items:center;justify-content:center">
                <x-icon name="weight" size="22" color="var(--pc-info-700,#1d4ed8)" />
            </div>

            <div style="flex:1;min-width:0">
                {{-- Peso em destaque --}}
                <div style="font-size:30px;font-weight:700;line-height:1;
                            color:var(--pc-n-900);letter-spacing:-.02em"
                     class="pc-mono">
                    {{ number_format($ultimaPesagem->peso_kg, 2, ',', '') }}
                    <span style="font-size:15px;font-weight:500;color:var(--pc-n-500)">kg</span>
                </div>

                {{-- Pet + data --}}
                @if($ultimaPesagem->pet)
                <div class="pc-body-strong" style="margin-top:6px;
                                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $ultimaPesagem->pet->nome }}
                </div>
                @endif
                <div class="pc-small" style="margin-top:2px">
                    {{ $ultimaPesagem->data->format('d/m/Y') }}
                    · {{ $ultimaPesagem->fonte }}
                </div>
            </div>
        </div>

        {{-- Subtotal de pesagens --}}
        <div style="margin-top:14px;padding:10px 12px;border-radius:var(--pc-r-sm);
                    background:var(--pc-n-50);display:flex;align-items:center;gap:8px">
            <x-icon name="trending_up" size="15" color="var(--pc-n-500)" />
            <span class="pc-small">Total de pesagens</span>
            <span class="pc-mono" style="font-weight:700;font-size:14px;
                                         color:var(--pc-n-900);margin-left:auto">
                {{ $totalPesagens }}
            </span>
        </div>

        <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--pc-n-100)">
            @if($ultimaPesagem->pet)
            <a href="{{ route('pets.show', $ultimaPesagem->pet) }}"
               class="pc-btn pc-btn-ghost pc-btn-sm"
               style="width:100%;justify-content:space-between">
                Ver histórico de {{ $ultimaPesagem->pet->nome }}
                <x-icon name="chevronRight" size="14" />
            </a>
            @endif
        </div>

        @else
        {{-- Estado vazio --}}
        <div style="padding:20px 0;text-align:center">
            <x-icon name="weight" size="32" color="var(--pc-n-300)" />
            <div class="pc-small" style="margin-top:8px">
                Nenhuma pesagem registrada ainda.
            </div>
        </div>
        <div style="margin-top:8px;padding-top:14px;border-top:1px solid var(--pc-n-100)">
            <a href="{{ route('pets.index') }}"
               class="pc-btn pc-btn-ghost pc-btn-sm"
               style="width:100%;justify-content:space-between">
                Ir para Meus Pets
                <x-icon name="chevronRight" size="14" />
            </a>
        </div>
        @endif

    </x-card>

</div>

{{-- ─── Ações rápidas ───────────────────────────────────────────────────── --}}

<div class="pc-grid-2" style="margin-top:20px">

    {{-- Atalhos de cadastro --}}
    <x-card>
        <div class="pc-h3" style="margin-bottom:16px">Ações rápidas</div>
        <div style="display:flex;flex-direction:column;gap:8px">

            <a href="{{ route('pets.create') }}"
               class="pc-btn pc-btn-secondary"
               style="justify-content:flex-start;gap:10px">
                <x-icon name="plus" size="16" />
                Cadastrar novo pet
            </a>

            @if($totalPets > 0)
            <a href="{{ route('pets.index') }}"
               class="pc-btn pc-btn-ghost"
               style="justify-content:flex-start;gap:10px">
                <x-icon name="paw" size="16" />
                Ver todos os pets
            </a>
            @endif

        </div>
    </x-card>

    {{-- Status geral --}}
    <x-card>
        <div class="pc-h3" style="margin-bottom:16px">Status geral</div>

        @if($totalPets === 0)
        <div style="text-align:center;padding:16px 0;color:var(--pc-n-500)">
            <x-icon name="info" size="28" color="var(--pc-n-300)" />
            <div class="pc-small" style="margin-top:8px">
                Comece cadastrando seu primeiro pet.
            </div>
        </div>
        @else
        <dl style="display:flex;flex-direction:column;gap:10px">

            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:8px 0;border-bottom:1px solid var(--pc-n-100)">
                <div style="display:flex;align-items:center;gap:8px">
                    <x-icon name="paw" size="15" color="var(--pc-success-500)" />
                    <span class="pc-small">Pets ativos</span>
                </div>
                <span class="pc-mono" style="font-weight:700;font-size:14px">{{ $totalAtivos }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:8px 0;border-bottom:1px solid var(--pc-n-100)">
                <div style="display:flex;align-items:center;gap:8px">
                    <x-icon name="weight" size="15" color="var(--pc-info-500,#3b82f6)" />
                    <span class="pc-small">Pesagens registradas</span>
                </div>
                <span class="pc-mono" style="font-weight:700;font-size:14px">{{ $totalPesagens }}</span>
            </div>

            @php
                $inativos = $totalPets - $totalAtivos;
            @endphp
            @if($inativos > 0)
            <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:8px 0">
                <div style="display:flex;align-items:center;gap:8px">
                    <x-icon name="clock" size="15" color="var(--pc-n-400)" />
                    <span class="pc-small">Pets inativos / falecidos</span>
                </div>
                <span class="pc-mono" style="font-weight:700;font-size:14px;
                                             color:var(--pc-n-500)">{{ $inativos }}</span>
            </div>
            @endif

        </dl>
        @endif

    </x-card>

</div>

@endsection
