{{--
    Partial compartilhado entre pets/create e pets/edit.
    $pet = null no create; instância de Pet no edit.
--}}

@push('scripts')
<script src="{{ asset('js/pet-form.js') }}"></script>
@endpush

<div class="pc-grid-2">

    {{-- Identificação --}}
    <x-card>
        <div class="pc-h3" style="margin-bottom:20px">Identificação</div>
        <div style="display:flex;flex-direction:column;gap:16px">

            <x-form.input
                name="nome"
                label="Nome *"
                placeholder="Como você chama seu pet"
                :value="old('nome', $pet?->nome)"
                :error="$errors->first('nome')"
                maxlength="60"
            />

            <x-form.select name="especie" label="Espécie *" :error="$errors->first('especie')">
                <option value="">Selecione…</option>
                @foreach(App\Models\Pet::ESPECIES as $especie)
                    <option value="{{ $especie }}"
                        @selected(old('especie', $pet?->especie) === $especie)>
                        {{ $especie }}
                    </option>
                @endforeach
            </x-form.select>

            <x-form.input
                name="raca"
                label="Raça"
                placeholder="Ex: Labrador, SRD, Persa…"
                :value="old('raca', $pet?->raca)"
                :error="$errors->first('raca')"
                maxlength="100"
            />

            <x-form.select name="sexo" label="Sexo *" :error="$errors->first('sexo')">
                <option value="">Selecione…</option>
                @foreach(App\Models\Pet::SEXOS as $sexo)
                    <option value="{{ $sexo }}"
                        @selected(old('sexo', $pet?->sexo) === $sexo)>
                        {{ $sexo }}
                    </option>
                @endforeach
            </x-form.select>

            <label class="pc-check-label">
                <input type="checkbox" name="castrado" value="1"
                       @checked(old('castrado', $pet?->castrado))>
                <span class="pc-checkbox">
                    <x-icon name="check" size="13" color="#fff" stroke="3" />
                </span>
                Castrado(a)
            </label>

        </div>
    </x-card>

    {{-- Detalhes físicos --}}
    <x-card>
        <div class="pc-h3" style="margin-bottom:20px">Detalhes físicos</div>
        <div style="display:flex;flex-direction:column;gap:16px">

            <x-form.input
                name="data_nascimento"
                type="date"
                label="Data de nascimento"
                :value="old('data_nascimento', $pet?->data_nascimento?->format('Y-m-d'))"
                :error="$errors->first('data_nascimento')"
                max="{{ date('Y-m-d') }}"
            />

            <x-form.input
                name="peso_atual"
                type="number"
                label="Peso atual (kg)"
                placeholder="Ex: 12.50"
                :value="old('peso_atual', $pet?->peso_atual)"
                :error="$errors->first('peso_atual')"
                step="0.01"
                min="0.01"
                max="999.99"
            />

            <x-form.input
                name="cor"
                label="Cor / pelagem"
                placeholder="Ex: Caramelo, Preto e branco…"
                :value="old('cor', $pet?->cor)"
                :error="$errors->first('cor')"
                maxlength="80"
            />

            <x-form.input
                name="microchip"
                label="Microchip"
                placeholder="Ex: 985141002512345"
                :value="old('microchip', $pet?->microchip)"
                :error="$errors->first('microchip')"
                maxlength="30"
                help="Número único — 15 dígitos padrão ISO."
            />

            <x-form.select name="status" label="Status *" :error="$errors->first('status')">
                @foreach(App\Models\Pet::STATUS as $s)
                    <option value="{{ $s }}"
                        @selected(old('status', $pet?->status ?? 'Ativo') === $s)>
                        {{ $s }}
                    </option>
                @endforeach
            </x-form.select>

        </div>
    </x-card>

</div>

{{-- Observações --}}
<x-card style="margin-top:20px">
    <x-form.textarea
        name="observacoes"
        label="Observações"
        placeholder="Alergias, hábitos alimentares, comportamento, histórico médico…"
        :error="$errors->first('observacoes')"
        rows="4"
    >{{ old('observacoes', $pet?->observacoes) }}</x-form.textarea>
</x-card>
