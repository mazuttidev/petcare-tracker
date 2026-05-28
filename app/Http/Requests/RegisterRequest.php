<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validação de servidor para o cadastro de novos usuários.
 *
 * IMPORTANTE: esta classe é a defesa real dos dados — roda no servidor
 * independentemente de qualquer validação feita no JavaScript do cliente.
 * Ao falhar, o Laravel redireciona ao formulário com $errors e old() preenchidos.
 */
class RegisterRequest extends FormRequest
{
    // Qualquer visitante pode tentar se registrar
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            // unique:users garante unicidade sem expor se o e-mail já existe
            // (a mensagem de erro genérica cobre isso)
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // confirmed exige que password_confirmation seja idêntico
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'O nome é obrigatório.',
            'name.max'           => 'O nome não pode ter mais de :max caracteres.',

            'email.required'     => 'O e-mail é obrigatório.',
            'email.email'        => 'Informe um e-mail válido.',
            'email.unique'       => 'Este e-mail já está em uso.',

            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'A confirmação de senha não coincide.',
        ];
    }
}
