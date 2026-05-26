@extends('layouts.app', ['activeNav' => 'home', 'title' => 'Styleguide — PetCare Tracker'])

@section('content')

{{-- ──────────────────────────────────────────────────────────
     STYLEGUIDE — sistema visual do PetCare Tracker
     Página de referência para conferir todos os componentes
     antes de construir as telas reais.
──────────────────────────────────────────────────────────── --}}

{{-- Cabeçalho da página --}}
<x-page-header title="Styleguide" subtitle="Componentes, tokens e padrões visuais do PetCare Tracker." />

{{-- ── 1. Paleta de cores ──────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Paleta — Coral primário</div>
    <div style="display:grid;grid-template-columns:repeat(9,1fr);gap:6px;margin-bottom:24px">
        @foreach([
            ['50',  '#FFF7F0'], ['100','#FEEDE0'], ['200','#FCD5B5'], ['300','#F8B485'],
            ['400','#F4925E'], ['500','#ED7A4A'], ['600','#D8632F'], ['700','#B14E22'], ['800','#813818'],
        ] as [$step, $hex])
        <div style="background:{{ $hex }};border-radius:10px;padding:12px 8px;min-height:80px;display:flex;flex-direction:column;justify-content:space-between;border:{{ $hex==='#FFF7F0'?'1px solid var(--pc-n-200)':'none' }}">
            <span class="pc-mono" style="font-size:11px;color:{{ (int)$step>=400?'rgba(255,255,255,.85)':'var(--pc-n-700)' }}">{{ $step }}</span>
            <span class="pc-mono" style="font-size:10px;color:{{ (int)$step>=400?'#fff':'var(--pc-n-500)' }}">{{ $hex }}</span>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
        @foreach([
            ['Sucesso', 'success', '#ECFDF3', '#D1FADF', '#12B76A', '#027A48'],
            ['Aviso',   'warning', '#FFFAEB', '#FEF0C7', '#F79009', '#B54708'],
            ['Erro',    'danger',  '#FEF3F2', '#FEE4E2', '#F04438', '#B42318'],
            ['Info',    'info',    '#EFF8FF', '#D1E9FF', '#2E90FA', '#175CD3'],
        ] as [$label, $v, $c50, $c100, $c500, $c700])
        <x-card>
            <div class="pc-h3" style="color:{{ $c700 }}">{{ $label }}</div>
            <div class="pc-small" style="margin-top:2px">Cor semântica</div>
            <div style="display:flex;gap:4px;margin-top:12px">
                @foreach([['50',$c50],['100',$c100],['500',$c500],['700',$c700]] as [$k,$hex])
                <div style="flex:1;border-radius:8px;background:{{ $hex }};height:52px;display:flex;flex-direction:column;justify-content:flex-end;padding:5px">
                    <span style="font-size:10px;font-weight:600;font-family:var(--pc-mono);color:{{ (int)$k>=500?'#fff':'var(--pc-n-800)' }}">{{ $k }}</span>
                </div>
                @endforeach
            </div>
        </x-card>
        @endforeach
    </div>
</section>

{{-- ── 2. Tipografia ───────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Tipografia — Plus Jakarta Sans</div>
    <x-card>
        <div style="display:flex;gap:32px;align-items:flex-start">
            <div style="width:200px;flex-shrink:0">
                <div style="font-size:72px;font-weight:700;line-height:1;color:var(--pc-primary-500)">Aa</div>
                <div class="pc-h3" style="margin-top:10px">Plus Jakarta Sans</div>
                <div class="pc-small">400 · 500 · 600 · 700</div>
                <hr class="pc-divider" style="margin:12px 0">
                <div style="font-size:17px;color:var(--pc-n-700);line-height:1.8">
                    ABCDEFGHIJ<br>abcdefghij<br>0123456789
                </div>
            </div>
            <div style="flex:1">
                @foreach([
                    ['Display',       'pc-display',     '40px / 700', 'Cuide do seu pet'],
                    ['Heading 1',     'pc-h1',          '28px / 700', 'Meus Pets'],
                    ['Heading 2',     'pc-h2',          '22px / 700', 'Próximas vacinas'],
                    ['Heading 3',     'pc-h3',          '18px / 600', 'Rex · Labrador'],
                    ['Body Strong',   'pc-body-strong', '15px / 600', 'Última pesagem: 32,4 kg'],
                    ['Body',          'pc-body',        '15px / 400', 'Registre vacinas, consultas e pesagens em um só lugar.'],
                    ['Small',         'pc-small',       '13px / 400', '3 lembretes pendentes esta semana'],
                    ['Caption',       'pc-caption',     '11px / 600 upper', 'PRÓXIMO VENCIMENTO'],
                ] as [$name, $cls, $spec, $sample])
                <div style="display:flex;align-items:baseline;gap:20px;padding:12px 0;border-bottom:1px solid var(--pc-n-100)">
                    <div style="width:120px;flex-shrink:0">
                        <div style="font-size:13px;font-weight:600">{{ $name }}</div>
                        <div class="pc-mono" style="font-size:10px;color:var(--pc-n-500)">{{ $spec }}</div>
                    </div>
                    <div class="{{ $cls }}" style="flex:1;color:var(--pc-n-900)">{{ $sample }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </x-card>
</section>

{{-- ── 3. Botões ───────────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Botões</div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px">
        <x-card>
            <div class="pc-body-strong">Primário</div>
            <div class="pc-small">Ação principal — uma por tela.</div>
            <div style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;margin-top:16px">
                <x-btn icon="plus">Novo pet</x-btn>
                <x-btn>Salvar alterações</x-btn>
                <x-btn disabled>Desabilitado</x-btn>
            </div>
        </x-card>
        <x-card>
            <div class="pc-body-strong">Secundário &amp; Ghost</div>
            <div class="pc-small">Ações alternativas, cancelar.</div>
            <div style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;margin-top:16px">
                <x-btn variant="secondary" icon="upload">Importar</x-btn>
                <x-btn variant="secondary">Cancelar</x-btn>
                <x-btn variant="ghost">Ver tudo</x-btn>
            </div>
        </x-card>
        <x-card>
            <div class="pc-body-strong">Perigo</div>
            <div class="pc-small">Destrutivos, irreversíveis.</div>
            <div style="display:flex;flex-direction:column;align-items:flex-start;gap:10px;margin-top:16px">
                <x-btn variant="danger" icon="trash">Apagar pet</x-btn>
                <x-btn variant="secondary" style="color:var(--pc-danger-700);border-color:var(--pc-danger-100)">Marcar como falecido</x-btn>
            </div>
        </x-card>
    </div>

    <x-card>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
            <div>
                <div class="pc-caption" style="margin-bottom:12px">Tamanhos</div>
                <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                    <x-btn size="sm">Pequeno</x-btn>
                    <x-btn>Médio</x-btn>
                    <x-btn size="lg">Grande</x-btn>
                </div>
            </div>
            <div>
                <div class="pc-caption" style="margin-bottom:12px">Ícone, combinado, loading</div>
                <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                    <button class="pc-btn pc-btn-secondary pc-btn-icon"><x-icon name="edit" size="18"/></button>
                    <button class="pc-btn pc-btn-secondary pc-btn-icon"><x-icon name="trash" size="18"/></button>
                    <x-btn icon="check">Confirmar</x-btn>
                    <x-btn :loading="true">Salvando…</x-btn>
                </div>
            </div>
        </div>
    </x-card>
</section>

{{-- ── 4. Cards ────────────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Cards</div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px">

        {{-- Card de métrica --}}
        <x-card>
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div>
                    <div class="pc-caption">Pets ativos</div>
                    <div style="font-size:36px;font-weight:700;color:var(--pc-n-900);margin-top:4px;line-height:1">4</div>
                </div>
                <div style="width:40px;height:40px;border-radius:12px;background:var(--pc-primary-100);display:flex;align-items:center;justify-content:center">
                    <x-icon name="paw" size="20" color="var(--pc-primary-700)" />
                </div>
            </div>
            <div class="pc-small" style="margin-top:14px;display:flex;align-items:center;gap:6px;color:var(--pc-success-700)">
                <x-icon name="trending_up" size="14" color="var(--pc-success-500)" /> +1 este mês
            </div>
        </x-card>

        {{-- Card de pet --}}
        <x-card :pad="false" style="overflow:hidden">
            <div class="pc-photo-placeholder" style="height:120px">foto do pet</div>
            <div style="padding:16px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div class="pc-h3">Rex</div>
                        <div class="pc-small">Labrador · macho · 4a</div>
                    </div>
                    <x-badge variant="success" dot>Ativo</x-badge>
                </div>
                <div class="pc-mono" style="font-size:11px;color:var(--pc-n-500);margin-top:8px">#PC-00142</div>
            </div>
        </x-card>

        {{-- Card de ação --}}
        <x-card style="display:flex;flex-direction:column;gap:10px">
            <div style="display:flex;align-items:center;gap:10px">
                <x-icon name="syringe" size="20" color="var(--pc-warning-500)" />
                <div class="pc-body-strong">Vacina antirrábica</div>
            </div>
            <div class="pc-small">Vence em 12 dias · Mel</div>
            <div style="display:flex;gap:8px;margin-top:4px">
                <x-btn size="sm">Agendar</x-btn>
                <x-btn size="sm" variant="ghost">Adiar</x-btn>
            </div>
        </x-card>
    </div>

    {{-- Card com lista --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
        <x-card :pad="false">
            <div style="padding:16px 20px;display:flex;justify-content:space-between;align-items:center">
                <div class="pc-h3">Últimas pesagens</div>
                <x-btn size="sm" variant="ghost">Ver tudo</x-btn>
            </div>
            <hr class="pc-divider">
            @foreach([['Rex','32,4 kg','17 mai','+0,3'],['Mel','5,8 kg','14 mai','−0,1'],['Tobi','12,2 kg','11 mai','+0,0']] as $i => [$n,$w,$d,$dt])
            <div style="display:flex;align-items:center;padding:14px 20px;gap:14px;{{ $i < 2 ? 'border-bottom:1px solid var(--pc-n-100)' : '' }}">
                <div class="pc-avatar" style="width:32px;height:32px;font-size:13px">{{ $n[0] }}</div>
                <div style="flex:1">
                    <div class="pc-body-strong" style="font-size:14px">{{ $n }}</div>
                    <div class="pc-small">{{ $d }}</div>
                </div>
                <div class="pc-mono" style="font-weight:600">{{ $w }}</div>
                <div class="pc-mono" style="font-size:12px;color:{{ str_starts_with($dt,'+') ? 'var(--pc-success-700)' : (str_starts_with($dt,'−') ? 'var(--pc-danger-700)' : 'var(--pc-n-500)') }};width:36px;text-align:right">{{ $dt }}</div>
            </div>
            @endforeach
        </x-card>

        {{-- Empty state --}}
        <x-card style="text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px 20px">
            <div style="width:56px;height:56px;border-radius:28px;background:var(--pc-primary-100);display:flex;align-items:center;justify-content:center;margin-bottom:14px">
                <x-icon name="paw" size="28" color="var(--pc-primary-700)" />
            </div>
            <div class="pc-body-strong">Nenhum pet ainda</div>
            <div class="pc-small" style="margin-top:4px">Cadastre o primeiro pra começar.</div>
            <x-btn size="sm" icon="plus" style="margin-top:14px">Cadastrar pet</x-btn>
        </x-card>
    </div>
</section>

{{-- ── 5. Badges ───────────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Badges de status</div>
    <x-card>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <x-badge variant="success" dot>Ativo</x-badge>
            <x-badge variant="neutral" dot>Falecido</x-badge>
            <x-badge variant="info"    dot>Doado</x-badge>
            <x-badge variant="danger"  dot>Perdido</x-badge>
            <x-badge variant="warning" dot>Atenção</x-badge>
            <div style="width:1px;height:24px;background:var(--pc-n-200)"></div>
            <x-badge variant="success">Vacinado</x-badge>
            <x-badge variant="warning">Vence em 12d</x-badge>
            <x-badge variant="info">Castrado</x-badge>
            <x-badge variant="neutral">SRD</x-badge>
        </div>
    </x-card>
</section>

{{-- ── 6. Alertas ──────────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Alertas</div>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
        <x-alert variant="success" title="Pet cadastrado com sucesso">O Rex já aparece em &ldquo;Meus Pets&rdquo;.</x-alert>
        <x-alert variant="warning" title="3 vacinas próximas do vencimento">Toque pra ver quais e agendar.</x-alert>
        <x-alert variant="danger"  title="Não foi possível salvar">Tente novamente em alguns segundos.</x-alert>
        <x-alert variant="info"    title="Dica">Pesar mensalmente ajuda a detectar mudanças cedo.</x-alert>
    </div>
</section>

{{-- ── 7. Campos de formulário ─────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Campos de formulário</div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px">
        <div>
            <div class="pc-caption" style="margin-bottom:12px">Normal</div>
            <div style="display:flex;flex-direction:column;gap:16px">
                <x-form.input name="nome_normal" label="Nome do pet" value="Rex" help="Apelido carinhoso conta." />
                <x-form.select name="especie_normal" label="Espécie">
                    <option>Cachorro</option>
                    <option>Gato</option>
                    <option>Outro</option>
                </x-form.select>
                <x-form.textarea name="obs_normal" label="Observações">Alérgico a frango. Ração premium 2x/dia.</x-form.textarea>
            </div>
        </div>
        <div>
            <div class="pc-caption" style="margin-bottom:12px">Com foco (simulado)</div>
            <div style="display:flex;flex-direction:column;gap:16px">
                <x-form.input name="nome_focus" label="Nome do pet" value="Re" style="border-color:var(--pc-primary-500);box-shadow:var(--pc-focus)" />
                <x-form.select name="especie_focus" label="Espécie" style="border-color:var(--pc-primary-500);box-shadow:var(--pc-focus)">
                    <option>Cachorro</option>
                </x-form.select>
                <x-form.textarea name="obs_focus" label="Observações" style="border-color:var(--pc-primary-500);box-shadow:var(--pc-focus)" />
            </div>
        </div>
        <div>
            <div class="pc-caption" style="margin-bottom:12px">Com erro</div>
            <div style="display:flex;flex-direction:column;gap:16px">
                <x-form.input name="nome_erro" label="Nome do pet" error="Nome é obrigatório." />
                <x-form.select name="especie_erro" label="Espécie" error="Selecione uma espécie.">
                    <option value="">Selecione…</option>
                </x-form.select>
                <x-form.textarea name="obs_erro" label="Observações" error="Observações muito longas." />
            </div>
        </div>
    </div>

    <hr class="pc-divider" style="margin:28px 0">

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:28px">
        <div>
            <div class="pc-caption" style="margin-bottom:12px">Checkbox</div>
            <div style="display:flex;flex-direction:column;gap:10px">
                <label class="pc-check-label">
                    <input type="checkbox" checked>
                    <span class="pc-checkbox">
                        <x-icon name="check" size="13" color="#fff" stroke="3" />
                    </span>
                    Cachorro
                </label>
                <label class="pc-check-label">
                    <input type="checkbox">
                    <span class="pc-checkbox"></span>
                    Gato
                </label>
                <label class="pc-check-label">
                    <input type="checkbox" checked>
                    <span class="pc-checkbox">
                        <x-icon name="check" size="13" color="#fff" stroke="3" />
                    </span>
                    Receber lembretes por e-mail
                </label>
            </div>
        </div>
        <div>
            <div class="pc-caption" style="margin-bottom:12px">Radio</div>
            <div style="display:flex;flex-direction:column;gap:10px">
                <label class="pc-check-label">
                    <input type="radio" name="sexo_demo" checked>
                    <span class="pc-radio-dot"></span>
                    Macho
                </label>
                <label class="pc-check-label">
                    <input type="radio" name="sexo_demo">
                    <span class="pc-radio-dot"></span>
                    Fêmea
                </label>
                <label class="pc-check-label">
                    <input type="radio" name="sexo_demo">
                    <span class="pc-radio-dot"></span>
                    Não informar
                </label>
            </div>
        </div>
        <div>
            <div class="pc-caption" style="margin-bottom:12px">Switch</div>
            <div style="display:flex;flex-direction:column;gap:14px">
                <label class="pc-check-label">
                    <input type="checkbox" checked>
                    <span class="pc-switch"></span>
                    Lembrete diário de medicação
                </label>
                <label class="pc-check-label">
                    <input type="checkbox">
                    <span class="pc-switch"></span>
                    Notificações por push
                </label>
            </div>
        </div>
    </div>
</section>

{{-- ── 8. Modal ────────────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Modal</div>
    <x-card style="display:flex;align-items:center;gap:16px">
        <x-btn onclick="pcOpenModal('modal-demo-apagar')" variant="danger" icon="trash">
            Abrir modal de confirmação
        </x-btn>
        <x-btn onclick="pcOpenModal('modal-demo-info')" variant="secondary">
            Abrir modal de informação
        </x-btn>
        <div class="pc-small">Pressione Esc ou clique fora para fechar.</div>
    </x-card>
</section>

{{-- ── 9. Tabs + Navegação lateral ─────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Tabs &amp; Navegação</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <x-card>
            <div class="pc-caption" style="margin-bottom:12px">Tabs</div>
            <div class="pc-tabs">
                <a href="#" class="pc-tab pc-tab--active">Geral</a>
                <a href="#" class="pc-tab">Saúde</a>
                <a href="#" class="pc-tab">Alimentação</a>
                <a href="#" class="pc-tab">Histórico</a>
            </div>
            <div style="padding:16px 0;color:var(--pc-n-600);font-size:14px">
                Conteúdo da aba selecionada aparece aqui.
            </div>
        </x-card>

        <x-card>
            <div class="pc-caption" style="margin-bottom:12px">Itens de navegação lateral</div>
            <nav class="pc-nav">
                <a href="#" class="pc-nav-item pc-nav-item--active">
                    <x-icon name="home" size="18" /> Dashboard
                </a>
                <a href="#" class="pc-nav-item">
                    <x-icon name="paw" size="18" /> Meus pets
                </a>
                <a href="#" class="pc-nav-item">
                    <x-icon name="syringe" size="18" /> Vacinas
                </a>
                <a href="#" class="pc-nav-item">
                    <x-icon name="stethoscope" size="18" /> Consultas
                </a>
            </nav>
        </x-card>
    </div>
</section>

{{-- ── 10. Ícones ──────────────────────────────────────────── --}}
<section style="margin-bottom:48px">
    <div class="pc-caption" style="margin-bottom:14px">Iconografia — linha 24×24</div>
    <x-card>
        <div style="display:grid;grid-template-columns:repeat(8,1fr);gap:8px">
            @foreach(['home','paw','calendar','syringe','stethoscope','pill','bowl','scale','bell','user','settings','plus','check','x','search','edit','trash','info','warning','check_circle','alert_circle','heart','weight','camera','logout','mail','lock','eye','clipboard','arrow_left','arrow_right','trending_up','clock','filter','upload','download'] as $iconName)
            <div style="padding:12px;border:1px solid var(--pc-n-100);border-radius:10px;display:flex;flex-direction:column;align-items:center;gap:8px">
                <x-icon :name="$iconName" size="22" color="var(--pc-n-700)" />
                <span class="pc-mono" style="font-size:9.5px;color:var(--pc-n-500);text-align:center;word-break:break-all">{{ $iconName }}</span>
            </div>
            @endforeach
        </div>
    </x-card>
</section>

{{-- Modais do styleguide --}}
@push('modals')

<x-modal id="modal-demo-apagar" title="Apagar Mel?" subtitle="Essa ação não pode ser desfeita. Todo o histórico será removido.">
    <x-alert variant="danger">
        Você perderá 24 vacinas, 8 consultas e 32 pesagens registradas.
    </x-alert>
    <x-slot name="footer">
        <x-btn variant="secondary" onclick="pcCloseModal('modal-demo-apagar')">Cancelar</x-btn>
        <x-btn variant="danger" icon="trash">Apagar pet</x-btn>
    </x-slot>
</x-modal>

<x-modal id="modal-demo-info" title="Informação" size="sm">
    <p class="pc-body" style="color:var(--pc-n-600)">
        Este é um modal de tamanho pequeno, ideal para confirmações simples ou mensagens breves.
    </p>
    <x-slot name="footer">
        <x-btn onclick="pcCloseModal('modal-demo-info')">Entendi</x-btn>
    </x-slot>
</x-modal>

@endpush

@endsection
