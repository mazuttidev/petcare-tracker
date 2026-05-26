{{--
    Partial compartilhado entre pesagens/create e pesagens/edit.
    Variáveis esperadas: $pet (sempre), $pesagem (null no create, instância no edit).
--}}

<x-card>
    <div class="pc-h3" style="margin-bottom:20px">Dados da pesagem</div>

    <div class="pc-form-grid-3">

        <x-form.input
            name="data"
            type="date"
            label="Data *"
            :value="old('data', $pesagem?->data?->format('Y-m-d') ?? date('Y-m-d'))"
            :error="$errors->first('data')"
            max="{{ date('Y-m-d') }}"
        />

        <x-form.input
            name="peso_kg"
            type="number"
            label="Peso (kg) *"
            placeholder="Ex: 12.50"
            :value="old('peso_kg', $pesagem?->peso_kg)"
            :error="$errors->first('peso_kg')"
            step="0.01"
            min="0.01"
            max="200"
        />

        <x-form.select name="fonte" label="Fonte *" :error="$errors->first('fonte')">
            @foreach(App\Models\Pesagem::FONTES as $fonte)
                <option value="{{ $fonte }}"
                    @selected(old('fonte', $pesagem?->fonte ?? 'Manual') === $fonte)>
                    {{ $fonte }}
                </option>
            @endforeach
        </x-form.select>

    </div>

    <div style="margin-top:16px">
        <x-form.textarea
            name="observacoes"
            label="Observações"
            placeholder="Contexto da pesagem, observações do veterinário…"
            :error="$errors->first('observacoes')"
            rows="3"
        >{{ old('observacoes', $pesagem?->observacoes) }}</x-form.textarea>
    </div>
</x-card>

{{--
    Bloco de confirmação de variação incomum.
    Só aparece quando PesagemRequest::withValidator() detecta > 30% de variação e
    adiciona o erro em 'confirmar_variacao'. O usuário deve marcar o checkbox e
    reenviar o formulário — o campo não é salvo no banco.
--}}
@if($errors->has('confirmar_variacao'))
<div style="margin-top:16px;padding:16px 20px;border-radius:var(--pc-r-md);
            border:1px solid var(--pc-warning-500);background:var(--pc-warning-50)">

    <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:12px">
        <x-icon name="warning" size="20" color="var(--pc-warning-700)" />
        <div>
            <div class="pc-body-strong" style="color:var(--pc-warning-700)">
                Variação incomum detectada
            </div>
            <div class="pc-small" style="color:var(--pc-warning-700);margin-top:4px">
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
