# PetCare Tracker — Contexto do Projeto

Trabalho de faculdade. Sistema de rastreamento de cuidados com pets.

## Stack

- **Backend:** Laravel 13 (PHP 8.5)
- **Views:** Blade (sem framework de front-end pesado)
- **Banco de dados:** MySQL 9 via Eloquent ORM
- **Front-end:** JavaScript Vanilla para interatividade

## Requisitos do Trabalho

- CRUD completo para as entidades principais
- Validação no cliente (JavaScript) e no servidor (Form Requests do Laravel)
- Layout responsivo
- Alguma interatividade via JavaScript Vanilla

## Regras de Segurança

- Usar sempre Eloquent ou Query Builder — nunca SQL cru concatenado (risco de SQL injection)
- Manter o escape padrão do Blade com `{{ }}` — nunca usar `{!! !!}` com dados do usuário
- Todo formulário deve ter `@csrf`
- Todos os models devem declarar `$fillable` para evitar mass assignment

## Padrões de Código

- Comentários em português
- Nomes de variáveis, métodos e classes claros e descritivos
- Seguir as convenções do Laravel (controllers no singular, rotas em kebab-case, etc.)

## Ambiente

- Servidor local: `php artisan serve` → http://127.0.0.1:8000
- Banco: `petcare_tracker` no MySQL local (root sem senha)
- `.env` já configurado com `DB_CONNECTION=mysql`
- CSS servido via `public/css/app.css` (fonte em `resources/css/app.css`); ao editar, copiar para public
- Styleguide visual disponível em http://127.0.0.1:8000/styleguide

## Rules de Autenticação

- Auth manual usando o sistema nativo do Laravel (facade Auth, guard de sessão), sem Laravel Breeze/Jetstream e sem Tailwind/Alpine — as telas seguem nosso design em Blade + CSS puro.
- Senhas sempre com hash (Hash::make / cast hashed), nunca em texto puro.
- Após login bem-sucedido, regenerar a sessão; no logout, invalidar a sessão e regenerar o token.
- Rotas de pets/pesagens/dashboard protegidas pelo middleware auth; rotas de login/registro pelo middleware guest.
- Cada usuário só enxerga e manipula os próprios pets (escopo por user_id).
- Validação de servidor via Form Requests; validação de cliente em JS Vanilla.

---

## Sistema Visual e Componentes Blade

### Como usar o layout mestre em novas views

Toda view real deve estender `layouts.app` e passar a variável `$activeNav`
para destacar o item correto na sidebar:

```blade
@extends('layouts.app', ['activeNav' => 'pets', 'title' => 'Meus Pets — PetCare'])

@section('content')
    {{-- conteúdo da página aqui --}}
@endsection
```

**Valores aceitos em `$activeNav`:**
`home` · `pets` · `vacinas` · `consultas` · `medicacoes` · `alimentacao` · `pesagens` · `perfil` · `configuracoes`

Para sobrescrever o botão "Novo pet" no topo:
```blade
@extends('layouts.app', ['activeNav' => 'pets'])

@section('content') ... @endsection

{{-- Slot topbarAction: substitui o botão padrão da topbar --}}
@slot('topbarAction')
    <x-btn icon="plus" href="{{ route('pets.create') }}">Novo pet</x-btn>
@endslot
```

### Componentes disponíveis

#### `<x-btn>` — Botão
```blade
<x-btn>Salvar</x-btn>
<x-btn variant="secondary" size="sm">Cancelar</x-btn>
<x-btn variant="danger" icon="trash">Apagar</x-btn>
<x-btn variant="ghost" href="{{ route('pets.index') }}">Ver tudo</x-btn>
<x-btn type="submit" :loading="$carregando">Salvando…</x-btn>
```
**Props:** `variant` (primary|secondary|ghost|danger) · `size` (sm|md|lg) · `icon` (nome do ícone) · `href` · `type` · `loading`

#### `<x-card>` — Card
```blade
<x-card>conteúdo com padding</x-card>
<x-card :pad="false">conteúdo sem padding (para listas)</x-card>
```

#### `<x-badge>` — Badge de status
```blade
<x-badge variant="success" dot>Ativo</x-badge>
<x-badge variant="warning">Vence em 12d</x-badge>
<x-badge variant="danger" dot>Perdido</x-badge>
```
**Variantes:** `success` · `warning` · `danger` · `info` · `neutral`

#### `<x-alert>` — Alerta
```blade
<x-alert variant="success" title="Salvo!">Registro criado com sucesso.</x-alert>
<x-alert variant="danger">Não foi possível salvar. Tente novamente.</x-alert>
```
**Variantes:** `success` · `warning` · `danger` · `info`

#### `<x-icon>` — Ícone SVG
```blade
<x-icon name="paw" size="20" color="var(--pc-primary-700)" />
```
Ícones disponíveis: `home` `paw` `syringe` `stethoscope` `pill` `bowl` `scale` `bell` `user` `settings` `plus` `check` `x` `search` `edit` `trash` `info` `warning` `check_circle` `alert_circle` `heart` `weight` `camera` `logout` `mail` `lock` `eye` `calendar` `trending_up` `clock` e outros.

#### `<x-page-header>` — Cabeçalho de página
```blade
<x-page-header title="Meus Pets" subtitle="4 pets cadastrados">
    <x-slot name="actions">
        <x-btn icon="plus" href="{{ route('pets.create') }}">Novo pet</x-btn>
    </x-slot>
</x-page-header>
```

#### `<x-modal>` — Modal
```blade
{{-- Botão de abertura --}}
<x-btn onclick="pcOpenModal('modal-apagar')">Apagar</x-btn>

{{-- Declaração do modal (dentro de @push('modals') no final da view) --}}
@push('modals')
<x-modal id="modal-apagar" title="Apagar pet?" subtitle="Esta ação não pode ser desfeita.">
    <x-alert variant="danger">Todo o histórico será removido.</x-alert>
    <x-slot name="footer">
        <x-btn variant="secondary" onclick="pcCloseModal('modal-apagar')">Cancelar</x-btn>
        <x-btn variant="danger" icon="trash" type="submit">Apagar</x-btn>
    </x-slot>
</x-modal>
@endpush
```

#### Campos de formulário
```blade
<form method="POST" action="{{ route('pets.store') }}">
    @csrf

    <x-form.input
        name="nome"
        label="Nome do pet"
        placeholder="Como você chama seu pet"
        :error="$errors->first('nome')"
    />

    <x-form.select name="especie" label="Espécie" :error="$errors->first('especie')">
        <option value="">Selecione…</option>
        <option value="cachorro" @selected(old('especie') === 'cachorro')>Cachorro</option>
        <option value="gato"     @selected(old('especie') === 'gato')>Gato</option>
    </x-form.select>

    <x-form.textarea
        name="observacoes"
        label="Observações"
        help="Alergias, hábitos, preferências."
        :error="$errors->first('observacoes')"
    />

    <x-btn type="submit">Salvar</x-btn>
</form>
```

### CSS — variáveis de design
As principais variáveis CSS estão em `resources/css/app.css` e podem ser usadas em qualquer estilo inline:
- Cores primárias: `var(--pc-primary-500)` (coral #ED7A4A), `var(--pc-primary-700)` (escuro)
- Neutros: `var(--pc-n-50)` (fundo) a `var(--pc-n-900)` (texto escuro)
- Semânticas: `var(--pc-success-500)` · `var(--pc-warning-500)` · `var(--pc-danger-500)` · `var(--pc-info-500)`
- Raios: `var(--pc-r-sm)` (8px) · `var(--pc-r-md)` (12px) · `var(--pc-r-lg)` (16px) · `var(--pc-r-pill)`
- Sombras: `var(--pc-sh-sm)` · `var(--pc-sh-md)` · `var(--pc-sh-lg)`
