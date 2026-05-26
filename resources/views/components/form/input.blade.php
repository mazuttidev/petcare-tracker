{{--
    Campo de input com label, ajuda e erro.
    Uso:
        <x-form.input name="nome" label="Nome do pet" placeholder="Como você chama seu pet" />
        <x-form.input name="email" type="email" label="E-mail" :error="$errors->first('email')" />
        <x-form.input name="nascimento" type="date" label="Data de nascimento" help="Informe o mais próximo possível." />
--}}
@props([
    'name',
    'label'       => null,
    'type'        => 'text',
    'help'        => null,
    'error'       => null,
    'value'       => null,
])

@php $descId = $name . '-desc'; @endphp

<div class="pc-field">
    @if($label)
        <label class="pc-field-label" for="{{ $name }}">{{ $label }}</label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if($error || $help) aria-describedby="{{ $descId }}" @endif
        @if($error) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => 'pc-input' . ($error ? ' pc-input--error' : '')]) }}
    />

    @if($error)
        <div id="{{ $descId }}" class="pc-field-error" role="alert" aria-live="polite">
            <x-icon name="alert_circle" size="13" aria-hidden="true" />
            {{ $error }}
        </div>
    @elseif($help)
        <div id="{{ $descId }}" class="pc-field-help">{{ $help }}</div>
    @endif
</div>
