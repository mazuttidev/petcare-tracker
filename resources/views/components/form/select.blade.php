{{--
    Campo select com label e erro.
    Uso:
        <x-form.select name="especie" label="Espécie" :error="$errors->first('especie')">
            <option value="">Selecione…</option>
            <option value="cachorro" @selected(old('especie') === 'cachorro')>Cachorro</option>
            <option value="gato"     @selected(old('especie') === 'gato')>Gato</option>
        </x-form.select>
--}}
@props([
    'name',
    'label' => null,
    'error' => null,
])

@php $descId = $name . '-desc'; @endphp

<div class="pc-field">
    @if($label)
        <label class="pc-field-label" for="{{ $name }}">{{ $label }}</label>
    @endif

    <div class="pc-select-wrap">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($error) aria-describedby="{{ $descId }}" aria-invalid="true" @endif
            {{ $attributes->merge(['class' => 'pc-select' . ($error ? ' pc-select--error' : '')]) }}
        >{{ $slot }}</select>
        <span class="pc-select-chevron" aria-hidden="true">
            <x-icon name="chevronDown" size="16" color="var(--pc-n-500)" />
        </span>
    </div>

    @if($error)
        <div id="{{ $descId }}" class="pc-field-error" role="alert" aria-live="polite">
            <x-icon name="alert_circle" size="13" aria-hidden="true" />
            {{ $error }}
        </div>
    @endif
</div>
