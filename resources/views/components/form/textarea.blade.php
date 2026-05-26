{{--
    Textarea com label, ajuda e erro.
    Uso:
        <x-form.textarea name="observacoes" label="Observações" placeholder="Ex: alergias, comportamento…" />
        <x-form.textarea name="descricao" label="Descrição" rows="5" :error="$errors->first('descricao')" />
--}}
@props([
    'name',
    'label'   => null,
    'help'    => null,
    'error'   => null,
    'rows'    => null,
    'value'   => null,
])

@php $descId = $name . '-desc'; @endphp

<div class="pc-field">
    @if($label)
        <label class="pc-field-label" for="{{ $name }}">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        @if($rows) rows="{{ $rows }}" @endif
        @if($error || $help) aria-describedby="{{ $descId }}" @endif
        @if($error) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => 'pc-textarea' . ($error ? ' pc-textarea--error' : '')]) }}
    >{{ old($name, $value ?? $slot) }}</textarea>

    @if($error)
        <div id="{{ $descId }}" class="pc-field-error" role="alert" aria-live="polite">
            <x-icon name="alert_circle" size="13" aria-hidden="true" />
            {{ $error }}
        </div>
    @elseif($help)
        <div id="{{ $descId }}" class="pc-field-help">{{ $help }}</div>
    @endif
</div>
