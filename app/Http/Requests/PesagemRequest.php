<?php

namespace App\Http\Requests;

use App\Models\Pesagem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validação de servidor para criação e edição de Pesagem.
 *
 * IMPORTANTE: esta classe é a defesa real dos dados — roda no servidor
 * independentemente de qualquer validação feita no JavaScript do cliente.
 *
 * Regras de campo estão em rules(). A regra de negócio de variação incomum
 * (> 30% em relação à pesagem anterior) está em withValidator(), pois exige
 * uma consulta ao banco e não bloqueia — apenas pede confirmação explícita.
 */
class PesagemRequest extends FormRequest
{
    // Sem sistema de autorização por enquanto — qualquer requisição é aceita
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pet     = $this->route('pet');
        $pesagem = $this->route('pesagem'); // null no store, instância no update

        return [
            // Uma pesagem por dia por pet; no update, ignora o próprio registro
            'data' => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('pesagens', 'data')
                    ->where(fn ($q) => $q->where('pet_id', $pet->id))
                    ->ignore($pesagem?->id),
            ],

            // Faixa fisiologicamente razoável para pets domésticos
            'peso_kg'    => ['required', 'numeric', 'min:0.01', 'max:200.00'],

            'fonte'       => ['required', Rule::in(Pesagem::FONTES)],
            'observacoes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'data.required'        => 'A data da pesagem é obrigatória.',
            'data.date'            => 'Informe uma data válida.',
            'data.before_or_equal' => 'A data não pode ser no futuro.',
            'data.unique'          => 'Já existe uma pesagem registrada para este pet nesta data.',

            'peso_kg.required'     => 'O peso é obrigatório.',
            'peso_kg.numeric'      => 'O peso deve ser um valor numérico.',
            'peso_kg.min'          => 'O peso mínimo aceito é 0,01 kg.',
            'peso_kg.max'          => 'O peso máximo aceito é 200,00 kg.',

            'fonte.required'       => 'Informe a fonte da pesagem.',
            'fonte.in'             => 'Fonte inválida. Valores aceitos: ' . implode(', ', Pesagem::FONTES) . '.',
        ];
    }

    /**
     * Verifica variação de peso incomum após as regras de campo passarem.
     *
     * Se o novo peso diferir > 30% da pesagem anterior do pet, adiciona um
     * erro em 'confirmar_variacao'. O formulário então exibe um aviso e um
     * checkbox — o usuário precisa marcar e reenviar para confirmar.
     *
     * O campo 'confirmar_variacao' não está em rules() porque não é salvo
     * no banco; serve apenas como confirmação explícita do usuário.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Usuário confirmou a variação na segunda tentativa — permite prosseguir
            if ($this->boolean('confirmar_variacao')) return;

            // Só verifica se os campos obrigatórios já passaram (evita duplo aviso)
            if ($validator->errors()->count()) return;

            $pet     = $this->route('pet');
            $pesagem = $this->route('pesagem'); // null no store
            $pesoNovo = (float) $this->input('peso_kg');

            if ($pesoNovo <= 0) return;

            // Pesagem mais recente do pet, excluindo o próprio registro no update
            $anterior = $pet->pesagens()
                ->when($pesagem, fn ($q) => $q->where('id', '!=', $pesagem->id))
                ->orderByDesc('data')
                ->first();

            if (!$anterior || (float) $anterior->peso_kg <= 0) return;

            $variacao = abs($pesoNovo - (float) $anterior->peso_kg) / (float) $anterior->peso_kg;

            if ($variacao > 0.30) {
                $validator->errors()->add(
                    'confirmar_variacao',
                    sprintf(
                        'Variação de %.0f%% em relação à pesagem anterior (%.2f kg em %s). '
                        . 'Confirme abaixo se o valor está correto.',
                        $variacao * 100,
                        $anterior->peso_kg,
                        $anterior->data->format('d/m/Y')
                    )
                );
            }
        });
    }
}
