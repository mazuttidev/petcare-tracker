<?php

namespace App\Http\Requests;

use App\Models\Pet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validação de servidor para criação e edição de Pet.
 *
 * IMPORTANTE: esta classe é a defesa real dos dados — ela roda no servidor
 * independentemente de qualquer validação feita no JavaScript do cliente.
 * A validação JS melhora a experiência do usuário, mas nunca substitui esta.
 *
 * Ao falhar, o Laravel redireciona automaticamente de volta ao formulário
 * com os erros em $errors e os valores anteriores disponíveis via old().
 */
class PetRequest extends FormRequest
{
    // Sem sistema de autorização por enquanto — qualquer requisição é aceita
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Recupera o Pet da rota: null no store, instância no update
        // Usado para que a regra unique do microchip ignore o próprio registro
        $pet = $this->route('pet');

        return [
            // Nome: obrigatório, até 60 chars, só letras (inclusive acentuadas),
            // espaços, hífen e apóstrofo. O flag /u ativa suporte a Unicode.
            'nome' => [
                'required',
                'string',
                'max:60',
                'regex:/^[\p{L}\s\'\-]+$/u',
            ],

            // Espécie e sexo: obrigatórios, restringidos aos valores do model
            'especie' => ['required', Rule::in(Pet::ESPECIES)],
            'sexo'    => ['required', Rule::in(Pet::SEXOS)],

            'raca' => ['nullable', 'string', 'max:100'],

            // Data de nascimento: opcional, mas não pode ser no futuro
            'data_nascimento' => ['nullable', 'date', 'before_or_equal:today'],

            // castrado já vem normalizado como bool pelo prepareForValidation()
            'castrado' => ['boolean'],

            // Peso: faixa fisiologicamente razoável para pets domésticos
            'peso_atual' => ['nullable', 'numeric', 'min:0.01', 'max:200.00'],

            'cor'        => ['nullable', 'string', 'max:80'],
            'observacoes'=> ['nullable', 'string'],

            // Microchip: único globalmente; no update ignora o próprio registro
            // whereNull garante que pets deletados (soft delete) não bloqueiem o valor
            'microchip' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('pets', 'microchip')
                    ->ignore($pet?->id)
                    ->whereNull('deleted_at'),
            ],

            'status' => ['required', Rule::in(Pet::STATUS)],
        ];
    }

    // Mensagens de erro em português
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do pet é obrigatório.',
            'nome.max'      => 'O nome não pode ter mais de :max caracteres.',
            'nome.regex'    => 'O nome aceita apenas letras, espaços, hífens e apóstrofos.',

            'especie.required' => 'Selecione a espécie do pet.',
            'especie.in'       => 'Espécie inválida. Valores aceitos: ' . implode(', ', Pet::ESPECIES) . '.',

            'sexo.required' => 'O sexo do pet é obrigatório.',
            'sexo.in'       => 'Sexo inválido. Escolha Macho ou Fêmea.',

            'data_nascimento.date'            => 'Informe uma data de nascimento válida.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser no futuro.',

            'peso_atual.numeric' => 'O peso deve ser um valor numérico.',
            'peso_atual.min'     => 'O peso mínimo aceito é 0,01 kg.',
            'peso_atual.max'     => 'O peso máximo aceito é 200,00 kg.',

            'microchip.max'    => 'O microchip não pode ter mais de :max caracteres.',
            'microchip.unique' => 'Este número de microchip já está cadastrado para outro pet.',

            'status.required' => 'O status do pet é obrigatório.',
            'status.in'       => 'Status inválido. Valores aceitos: ' . implode(', ', Pet::STATUS) . '.',
        ];
    }

    /**
     * Normaliza os dados antes da validação.
     *
     * O checkbox HTML não envia nenhum valor quando desmarcado, então
     * precisamos garantir que 'castrado' chegue como boolean em ambos os casos.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'castrado' => $this->boolean('castrado'),
        ]);
    }
}
