@extends('layouts.app', ['activeNav' => 'pets', 'title' => $pet->nome . ' — PetCare'])

@section('content')

@php
    $badgeVariant = match($pet->status) {
        'Ativo'    => 'success',
        'Falecido' => 'neutral',
        'Doado'    => 'info',
        'Perdido'  => 'danger',
        default    => 'neutral',
    };

    // Ordem cronológica para cálculos e gráfico
    $pesagensOrdenadas = $pet->pesagens->sortBy('data')->values();
    $totalPesagens     = $pesagensOrdenadas->count();

    // Verifica se a última variação ultrapassa ±10%
    $alertaVariacao = null;
    if ($totalPesagens >= 2) {
        $ultima    = $pesagensOrdenadas->last();
        $penultima = $pesagensOrdenadas[$totalPesagens - 2];
        if ((float) $penultima->peso_kg > 0) {
            $variacaoPct = round(
                ((float) $ultima->peso_kg - (float) $penultima->peso_kg)
                / (float) $penultima->peso_kg * 100,
                1
            );
            if (abs($variacaoPct) > 10) {
                $alertaVariacao = $variacaoPct;
            }
        }
    }
@endphp

{{-- ─── Cabeçalho ────────────────────────────────────────────────────────── --}}

<x-page-header :title="$pet->nome"
               subtitle="{{ $pet->especie }}{{ $pet->raca ? ' · ' . $pet->raca : '' }}">
    <x-slot name="actions">
        <x-btn variant="secondary" icon="edit" href="{{ route('pets.edit', $pet) }}">Editar</x-btn>
        <x-btn variant="danger"    icon="trash" onclick="pcOpenModal('modal-excluir-pet')">Remover</x-btn>
    </x-slot>
</x-page-header>

{{-- ─── Alerta de variação ≥ ±10% ──────────────────────────────────────── --}}

@if($alertaVariacao !== null)
@php $aumento = $alertaVariacao > 0; @endphp
<x-alert variant="danger"
         title="{{ $aumento ? 'Aumento' : 'Queda' }} brusco de peso detectado">
    A última pesagem registrou uma variação de
    <strong>{{ $aumento ? '+' : '' }}{{ number_format($alertaVariacao, 1, ',', '') }}%</strong>
    em relação à anterior. Variações acima de ±10% merecem atenção —
    recomendamos uma <strong>consulta veterinária</strong>.
</x-alert>
<div style="margin-bottom:20px"></div>
@endif

{{-- ─── Dados do pet + lista de pesagens ───────────────────────────────── --}}

<div class="pc-grid-2">

    {{-- Dados gerais --}}
    <x-card>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <div class="pc-h3">Dados gerais</div>
            <x-badge :variant="$badgeVariant" dot>{{ $pet->status }}</x-badge>
        </div>

        <dl style="display:grid;grid-template-columns:1fr 1fr;gap:16px 24px">
            <div>
                <div class="pc-caption">Espécie</div>
                <div class="pc-body-strong" style="margin-top:4px">{{ $pet->especie }}</div>
            </div>
            @if($pet->raca)
            <div>
                <div class="pc-caption">Raça</div>
                <div class="pc-body-strong" style="margin-top:4px">{{ $pet->raca }}</div>
            </div>
            @endif
            <div>
                <div class="pc-caption">Sexo</div>
                <div class="pc-body-strong" style="margin-top:4px">{{ $pet->sexo }}</div>
            </div>
            <div>
                <div class="pc-caption">Castrado</div>
                <div class="pc-body-strong" style="margin-top:4px">{{ $pet->castrado ? 'Sim' : 'Não' }}</div>
            </div>
            @if($pet->data_nascimento)
            <div>
                <div class="pc-caption">Nascimento</div>
                <div class="pc-body-strong" style="margin-top:4px">
                    {{ $pet->data_nascimento->format('d/m/Y') }}
                    <span class="pc-small">({{ $pet->data_nascimento->diffForHumans(null, true) }})</span>
                </div>
            </div>
            @endif
            @if($pet->cor)
            <div>
                <div class="pc-caption">Cor / pelagem</div>
                <div class="pc-body-strong" style="margin-top:4px">{{ $pet->cor }}</div>
            </div>
            @endif
            @if($pet->microchip)
            <div>
                <div class="pc-caption">Microchip</div>
                <div class="pc-body-strong pc-mono" style="margin-top:4px;font-size:13px">{{ $pet->microchip }}</div>
            </div>
            @endif
            @if($pet->peso_atual)
            <div>
                <div class="pc-caption">Peso atual</div>
                <div class="pc-body-strong pc-mono" style="margin-top:4px;color:var(--pc-primary-700);font-size:16px">
                    {{ number_format($pet->peso_atual, 2, ',', '') }} kg
                </div>
            </div>
            @endif
        </dl>

        @if($pet->observacoes)
        <hr class="pc-divider" style="margin:18px 0">
        <div class="pc-caption" style="margin-bottom:6px">Observações</div>
        <div class="pc-body" style="color:var(--pc-n-700);white-space:pre-line">{{ $pet->observacoes }}</div>
        @endif
    </x-card>

    {{-- Lista de pesagens --}}
    <x-card :pad="false">
        <div style="padding:16px 20px;display:flex;justify-content:space-between;align-items:center">
            <div>
                <div class="pc-h3">Pesagens</div>
                <div class="pc-small" style="margin-top:2px">
                    {{ $totalPesagens }} registro{{ $totalPesagens !== 1 ? 's' : '' }}
                </div>
            </div>
            <a href="#form-nova-pesagem"
               class="pc-btn pc-btn-primary pc-btn-sm"
               style="display:inline-flex;align-items:center;gap:6px">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Registrar
            </a>
        </div>
        <hr class="pc-divider">

        @forelse($pesagensOrdenadas->reverse()->take(8) as $pesagem)
        <div style="display:flex;align-items:center;padding:10px 20px;gap:12px;
                    border-bottom:1px solid var(--pc-n-100)">
            <div style="flex:1">
                <div class="pc-body-strong" style="font-size:14px">
                    {{ $pesagem->data->format('d/m/Y') }}
                </div>
                <div class="pc-small">{{ $pesagem->fonte }}</div>
            </div>
            <div class="pc-mono" style="font-size:15px;font-weight:700;color:var(--pc-n-900)">
                {{ number_format($pesagem->peso_kg, 2, ',', '') }} kg
            </div>
            <div style="display:flex;gap:2px">
                <a href="{{ route('pets.pesagens.edit', [$pet, $pesagem]) }}"
                   class="pc-btn pc-btn-ghost pc-btn-icon pc-btn-sm"
                   aria-label="Editar pesagem de {{ $pesagem->data->format('d/m/Y') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </a>
                <button type="button"
                        class="pc-btn pc-btn-ghost pc-btn-icon pc-btn-sm"
                        style="color:var(--pc-danger-500)"
                        aria-label="Remover pesagem de {{ $pesagem->data->format('d/m/Y') }}"
                        data-confirmar-excluir-pesagem
                        data-url="{{ route('pets.pesagens.destroy', [$pet, $pesagem]) }}"
                        data-info="{{ $pesagem->data->format('d/m/Y') }} · {{ number_format($pesagem->peso_kg, 2, ',', '') }} kg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <div style="padding:32px 20px;text-align:center;color:var(--pc-n-500)">
            Nenhuma pesagem registrada ainda.
        </div>
        @endforelse
    </x-card>

</div>

{{-- ─── Gráfico de evolução do peso (3+ pesagens) ──────────────────────── --}}

@if($totalPesagens >= 3)
<x-card style="margin-top:20px;position:relative;overflow:visible">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px">
        <div>
            <div class="pc-h3">Evolução do peso</div>
            <div class="pc-small" style="margin-top:2px">
                {{ $pesagensOrdenadas->first()->data->format('d/m/Y') }}
                → {{ $pesagensOrdenadas->last()->data->format('d/m/Y') }}
                · {{ $totalPesagens }} medições
            </div>
        </div>
        @if($pet->peso_atual)
        <div style="text-align:right">
            <div class="pc-caption">Peso atual</div>
            <div class="pc-mono" style="font-size:20px;font-weight:700;color:var(--pc-primary-600,#d95f28);margin-top:2px">
                {{ number_format($pet->peso_atual, 2, ',', '') }} kg
            </div>
        </div>
        @endif
    </div>
    <canvas id="grafico-peso" style="display:block;width:100%"></canvas>
</x-card>

{{-- Tooltip do gráfico (position:fixed para sobrepor qualquer elemento) --}}
<div id="grafico-tooltip" style="
    position:fixed;display:none;pointer-events:none;z-index:9999;
    background:var(--pc-n-900);color:var(--pc-n-50);
    padding:7px 12px;border-radius:var(--pc-r-sm);
    box-shadow:var(--pc-sh-md);min-width:100px">
    <div id="tt-data"  style="font-size:11px;opacity:.7;margin-bottom:1px"></div>
    <div id="tt-peso"  style="font-size:15px;font-weight:700;font-family:var(--pc-mono)"></div>
    <div id="tt-fonte" style="font-size:11px;opacity:.6;margin-top:1px"></div>
</div>

{{-- Pré-computa os dados do gráfico para evitar vírgulas dentro do @json() --}}
@php
$dadosGrafico = $pesagensOrdenadas->map(fn($p) => [
    'label' => $p->data->format('d/m'),
    'data'  => $p->data->format('d/m/Y'),
    'peso'  => (float) $p->peso_kg,
    'fonte' => $p->fonte,
])->values();
@endphp
<script>
var DADOS_GRAFICO = @json($dadosGrafico);
</script>
@endif

{{-- ─── Formulário inline: registrar pesagem ───────────────────────────── --}}

<x-card id="form-nova-pesagem" style="margin-top:20px">
    <div class="pc-h3" style="margin-bottom:4px">Registrar pesagem</div>
    <div class="pc-small" style="margin-bottom:20px">
        Registra e atualiza automaticamente o peso atual de {{ $pet->nome }}.
    </div>

    <form method="POST" action="{{ route('pets.pesagens.store', $pet) }}" id="form-pesagem-inline">
        @csrf

        <div class="pc-form-grid-3">
            <x-form.input
                name="data"
                type="date"
                label="Data *"
                :value="old('data', date('Y-m-d'))"
                :error="$errors->first('data')"
                max="{{ date('Y-m-d') }}"
            />
            <x-form.input
                name="peso_kg"
                type="number"
                label="Peso (kg) *"
                placeholder="Ex: 12.50"
                :value="old('peso_kg')"
                :error="$errors->first('peso_kg')"
                step="0.01"
                min="0.01"
                max="200"
            />
            <x-form.select name="fonte" label="Fonte *" :error="$errors->first('fonte')">
                @foreach(App\Models\Pesagem::FONTES as $fonte)
                    <option value="{{ $fonte }}"
                        @selected(old('fonte', 'Manual') === $fonte)>
                        {{ $fonte }}
                    </option>
                @endforeach
            </x-form.select>
        </div>

        <div style="margin-top:16px">
            <x-form.textarea
                name="observacoes"
                label="Observações"
                placeholder="Contexto da pesagem, nota do veterinário…"
                :error="$errors->first('observacoes')"
                rows="2"
            >{{ old('observacoes') }}</x-form.textarea>
        </div>

        {{--
            Bloco de confirmação de variação incomum.
            Só aparece quando PesagemRequest detecta > 30% de variação
            e adiciona o erro em 'confirmar_variacao'.
        --}}
        @if($errors->has('confirmar_variacao'))
        <div style="margin-top:16px;padding:14px 16px;
                    border-radius:var(--pc-r-md);
                    border:1.5px solid var(--pc-warning-500);
                    background:var(--pc-warning-50)">
            <div style="display:flex;gap:10px;align-items:flex-start;margin-bottom:10px">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="var(--pc-warning-700)" stroke-width="1.75"
                     stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <div>
                    <div class="pc-body-strong" style="color:var(--pc-warning-700)">Variação incomum detectada</div>
                    <div class="pc-small" style="color:var(--pc-warning-700);margin-top:2px">
                        {{ $errors->first('confirmar_variacao') }}
                    </div>
                </div>
            </div>
            <label class="pc-check-label">
                <input type="checkbox" name="confirmar_variacao" value="1"
                       @checked(old('confirmar_variacao'))>
                <span class="pc-checkbox">
                    <x-icon name="check" size="13" color="#fff" stroke="3" />
                </span>
                Confirmo que o valor está correto e desejo salvar mesmo assim.
            </label>
        </div>
        @endif

        <div style="display:flex;gap:10px;margin-top:20px;padding-top:16px;
                    border-top:1px solid var(--pc-n-100)">
            <x-btn type="submit" icon="check">Salvar pesagem</x-btn>
            <x-btn variant="ghost" href="{{ route('pets.pesagens.create', $pet) }}">
                Formulário completo
            </x-btn>
        </div>
    </form>
</x-card>

<div style="margin-top:16px">
    <x-btn variant="ghost" icon="arrow_left" href="{{ route('pets.index') }}">
        Voltar para Meus Pets
    </x-btn>
</div>

{{-- ─── Formulários ocultos para exclusão ─────────────────────────────── --}}

<form id="form-excluir-pet" method="POST"
      action="{{ route('pets.destroy', $pet) }}" style="display:none">
    @csrf
    @method('DELETE')
</form>

<form id="form-excluir-pesagem" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

{{-- ─── Modais de confirmação ──────────────────────────────────────────── --}}

@push('modals')

<x-modal id="modal-excluir-pet" size="sm"
         :title="'Remover ' . $pet->nome . '?'"
         subtitle="Esta ação pode ser desfeita — o registro vai para a lixeira.">
    <x-alert variant="warning">
        O histórico de pesagens de {{ $pet->nome }} ficará inacessível
        enquanto o pet estiver na lixeira.
    </x-alert>
    <x-slot name="footer">
        <x-btn variant="secondary" onclick="pcCloseModal('modal-excluir-pet')">Cancelar</x-btn>
        <x-btn variant="danger" icon="trash" type="submit" form="form-excluir-pet">
            Sim, remover
        </x-btn>
    </x-slot>
</x-modal>

<x-modal id="modal-excluir-pesagem" size="sm" title="Remover pesagem?">
    <p class="pc-body" style="color:var(--pc-n-700)">
        Remover a pesagem de
        <strong id="modal-pesagem-info" style="color:var(--pc-n-900)"></strong>?
    </p>
    <p class="pc-small" style="color:var(--pc-n-500);margin-top:8px">
        Esta ação não pode ser desfeita.
    </p>
    <x-slot name="footer">
        <x-btn variant="secondary" onclick="pcCloseModal('modal-excluir-pesagem')">Cancelar</x-btn>
        <x-btn variant="danger" icon="trash" type="submit" form="form-excluir-pesagem">
            Sim, remover
        </x-btn>
    </x-slot>
</x-modal>

@endpush

@push('scripts')
<script>
// ─────────────────────────────────────────────────────────────────────────────
// GRÁFICO DE EVOLUÇÃO DO PESO
// Canvas puro — sem biblioteca externa.
// ─────────────────────────────────────────────────────────────────────────────
(function () {
    'use strict';

    var canvas = document.getElementById('grafico-peso');
    if (!canvas) return;

    var dados = window.DADOS_GRAFICO || [];
    if (dados.length < 3) return;

    var ctx     = canvas.getContext('2d');
    var tooltip = document.getElementById('grafico-tooltip');

    // Tokens de cor (espelham o design system)
    var COR_LINHA  = '#ED7A4A';  // --pc-primary-500
    var COR_GRADE  = '#EAE3DA';  // --pc-n-200
    var COR_TEXTO  = '#9E8F83';  // --pc-n-400
    var PAD = { t: 20, r: 20, b: 48, l: 56 };

    // Intervalo Y com margem de 20%
    var pesos  = dados.map(function (d) { return d.peso; });
    var pMin   = Math.min.apply(null, pesos);
    var pMax   = Math.max.apply(null, pesos);
    var margem = Math.max((pMax - pMin) * 0.20, 0.5);
    var yMin   = Math.max(0, pMin - margem);
    var yMax   = pMax + margem;

    function xDe(i) {
        return PAD.l + (i / (dados.length - 1)) * (canvas.width - PAD.l - PAD.r);
    }
    function yDe(p) {
        return PAD.t + (1 - (p - yMin) / (yMax - yMin)) * (canvas.height - PAD.t - PAD.b);
    }

    function desenhar(prog) {
        var W = canvas.width, H = canvas.height;
        ctx.clearRect(0, 0, W, H);

        // Grade horizontal + labels Y
        var TICKS = 5;
        for (var ti = 0; ti <= TICKS; ti++) {
            var val = yMax - (ti / TICKS) * (yMax - yMin);
            var gy  = yDe(val);

            ctx.beginPath();
            ctx.moveTo(PAD.l, gy);
            ctx.lineTo(W - PAD.r, gy);
            ctx.strokeStyle = COR_GRADE;
            ctx.lineWidth   = 1;
            ctx.stroke();

            ctx.fillStyle    = COR_TEXTO;
            ctx.font         = '11px system-ui,sans-serif';
            ctx.textAlign    = 'right';
            ctx.textBaseline = 'middle';
            ctx.fillText(val.toFixed(1), PAD.l - 8, gy);
        }

        // Labels X (datas, máx. 8 visíveis)
        var maxLbls = Math.min(dados.length, 8);
        var passo   = Math.max(1, Math.ceil(dados.length / maxLbls));
        ctx.fillStyle    = COR_TEXTO;
        ctx.textAlign    = 'center';
        ctx.textBaseline = 'top';
        dados.forEach(function (d, i) {
            if (i % passo === 0 || i === dados.length - 1) {
                ctx.fillText(d.label, xDe(i), H - PAD.b + 10);
            }
        });

        // Progresso animado
        var segs = prog * (dados.length - 1);
        var nInt = Math.floor(segs);
        var frac = segs - nInt;
        var nPts = nInt + 1;

        var xFim = nInt < dados.length - 1
            ? xDe(nInt) + frac * (xDe(nInt + 1) - xDe(nInt))
            : xDe(dados.length - 1);
        var yFim = nInt < dados.length - 1
            ? yDe(dados[nInt].peso) + frac * (yDe(dados[nInt + 1].peso) - yDe(dados[nInt].peso))
            : yDe(dados[dados.length - 1].peso);

        // Preenchimento gradiente sob a linha
        var grad = ctx.createLinearGradient(0, PAD.t, 0, H - PAD.b);
        grad.addColorStop(0, 'rgba(237,122,74,.15)');
        grad.addColorStop(1, 'rgba(237,122,74,.00)');
        ctx.beginPath();
        ctx.moveTo(xDe(0), yDe(dados[0].peso));
        for (var i = 1; i < nPts; i++) ctx.lineTo(xDe(i), yDe(dados[i].peso));
        ctx.lineTo(xFim, yFim);
        ctx.lineTo(xFim, H - PAD.b);
        ctx.lineTo(xDe(0), H - PAD.b);
        ctx.closePath();
        ctx.fillStyle = grad;
        ctx.fill();

        // Linha
        ctx.beginPath();
        ctx.moveTo(xDe(0), yDe(dados[0].peso));
        for (var j = 1; j < nPts; j++) ctx.lineTo(xDe(j), yDe(dados[j].peso));
        ctx.lineTo(xFim, yFim);
        ctx.strokeStyle = COR_LINHA;
        ctx.lineWidth   = 2.5;
        ctx.lineJoin    = 'round';
        ctx.lineCap     = 'round';
        ctx.stroke();

        // Pontos (só aparecem completamente após o progresso passar pelo índice)
        for (var k = 0; k < nPts; k++) {
            var px = xDe(k), py = yDe(dados[k].peso);
            var isUltimo = (k === dados.length - 1) && prog >= 1;

            if (isUltimo) { ctx.shadowColor = 'rgba(237,122,74,.4)'; ctx.shadowBlur = 10; }
            ctx.beginPath();
            ctx.arc(px, py, isUltimo ? 6 : 4.5, 0, Math.PI * 2);
            ctx.fillStyle   = '#FFFFFF';
            ctx.fill();
            ctx.strokeStyle = COR_LINHA;
            ctx.lineWidth   = isUltimo ? 2.5 : 2;
            ctx.stroke();
            if (isUltimo) { ctx.shadowColor = 'transparent'; ctx.shadowBlur = 0; }
        }
    }

    // Animação de entrada
    var t0 = null, DUR = 700;
    function tick(ts) {
        if (!t0) t0 = ts;
        var p    = Math.min(1, (ts - t0) / DUR);
        var ease = 1 - Math.pow(1 - p, 3); // cubic ease-out
        desenhar(ease);
        if (p < 1) requestAnimationFrame(tick);
    }

    // Responsivo: redesenha quando o container redimensiona
    function resize() {
        canvas.width  = canvas.parentElement.clientWidth;
        canvas.height = 270;
        desenhar(1);
    }
    window.addEventListener('resize', resize);
    canvas.width  = canvas.parentElement.clientWidth;
    canvas.height = 270;
    requestAnimationFrame(tick);

    // Tooltip hover
    var hovIdx = null;
    canvas.style.cursor = 'crosshair';

    canvas.addEventListener('mousemove', function (e) {
        var rect   = canvas.getBoundingClientRect();
        var scaleX = canvas.width / rect.width;
        var mx     = (e.clientX - rect.left) * scaleX;

        var best = null, bestDist = 36 * scaleX;
        dados.forEach(function (d, i) {
            var dist = Math.abs(xDe(i) - mx);
            if (dist < bestDist) { bestDist = dist; best = i; }
        });

        if (best !== null) {
            if (best !== hovIdx) {
                hovIdx = best;
                var d   = dados[best];
                var scY = canvas.height / rect.height;
                var cx  = rect.left + xDe(best) / scaleX;
                var cy  = rect.top  + yDe(d.peso) / scY;

                document.getElementById('tt-data').textContent  = d.data;
                document.getElementById('tt-peso').textContent  =
                    d.peso.toFixed(2).replace('.', ',') + ' kg';
                document.getElementById('tt-fonte').textContent = d.fonte;

                tooltip.style.display = 'block';
                var tw = tooltip.offsetWidth, th = tooltip.offsetHeight;
                tooltip.style.left = (cx - tw / 2) + 'px';
                tooltip.style.top  = (cy - th - 14) + 'px';
            }
        } else {
            hovIdx = null;
            tooltip.style.display = 'none';
        }
    });

    canvas.addEventListener('mouseleave', function () {
        hovIdx = null;
        tooltip.style.display = 'none';
    });
})();

// ─────────────────────────────────────────────────────────────────────────────
// VALIDAÇÃO DO FORMULÁRIO INLINE DE PESAGEM
//
// ATENÇÃO — APENAS EXPERIÊNCIA DO USUÁRIO
// A validação real é feita no servidor via PesagemRequest.php.
// ─────────────────────────────────────────────────────────────────────────────
(function () {
    'use strict';

    var form = document.getElementById('form-pesagem-inline');
    if (!form) return;

    var ICON_ALERTA =
        '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"' +
        ' fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"' +
        ' stroke-linejoin="round" aria-hidden="true">' +
        '<circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>';

    // Espelha as regras de PesagemRequest.php
    var REGRAS = {
        data: function (v) {
            if (!v) return 'A data é obrigatória.';
            if (v > new Date().toISOString().slice(0, 10)) return 'A data não pode ser no futuro.';
            return null;
        },
        peso_kg: function (v) {
            if (!v) return 'O peso é obrigatório.';
            var n = parseFloat(v.replace(',', '.'));
            if (isNaN(n))  return 'O peso deve ser numérico.';
            if (n < 0.01)  return 'O peso mínimo é 0,01 kg.';
            if (n > 200)   return 'O peso máximo é 200,00 kg.';
            return null;
        },
        fonte: function (v) {
            return v ? null : 'Informe a fonte.';
        },
    };

    function mostrarErro(campo, msg) {
        var wrapper = campo.closest('.pc-field');
        if (!wrapper) return;
        wrapper.querySelectorAll('.pc-field-error').forEach(function (el) { el.remove(); });
        var classeErro = campo.tagName === 'SELECT' ? 'pc-select--error' : 'pc-input--error';
        if (msg) {
            campo.classList.add(classeErro);
            var div = document.createElement('div');
            div.className = 'pc-field-error pc-field-error--js';
            div.innerHTML = ICON_ALERTA + '<span>' + msg + '</span>';
            var help = wrapper.querySelector('.pc-field-help');
            help ? wrapper.insertBefore(div, help) : wrapper.appendChild(div);
        } else {
            campo.classList.remove(classeErro);
        }
    }

    function validar(campo) {
        var regra = REGRAS[campo.name];
        if (!regra) return true;
        var erro = regra(campo.value);
        mostrarErro(campo, erro);
        return erro === null;
    }

    Object.keys(REGRAS).forEach(function (nome) {
        var campo = form.querySelector('[name="' + nome + '"]');
        if (!campo) return;
        campo.addEventListener('blur',   function () { validar(campo); });
        campo.addEventListener('change', function () { validar(campo); });
        if (campo.tagName === 'INPUT' && campo.type === 'number') {
            campo.addEventListener('input', function () { validar(campo); });
        }
    });

    form.addEventListener('submit', function (e) {
        var ok = true;
        Object.keys(REGRAS).forEach(function (nome) {
            var campo = form.querySelector('[name="' + nome + '"]');
            if (campo && !validar(campo)) ok = false;
        });
        if (!ok) {
            e.preventDefault();
            var primeiro = form.querySelector('.pc-field-error--js');
            if (primeiro) primeiro.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
})();

// ─────────────────────────────────────────────────────────────────────────────
// MODAL DE EXCLUSÃO DE PESAGEM
// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-confirmar-excluir-pesagem]');
    if (!btn) return;
    document.getElementById('form-excluir-pesagem').action = btn.dataset.url;
    document.getElementById('modal-pesagem-info').textContent = btn.dataset.info;
    pcOpenModal('modal-excluir-pesagem');
});
</script>
@endpush

@endsection
