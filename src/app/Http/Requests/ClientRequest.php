<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client');
    
        // regras padrão para PATCH (atualização parcial)
        $rules = [
            'full_name' => 'sometimes|string|max:255',
            'cpf'       => ['sometimes', 'string', "unique:clients,cpf,{$clientId}", new ValidCpf],
            'email'     => ['sometimes', 'email', "unique:clients,email,{$clientId}"],
            'phone'     => 'sometimes|nullable|string|max:15',
            'cep'       => 'sometimes|string|size:9'
        ];
    
        // se for POST ou PUT, todos os campos se tornam obrigatórios (required)
        if ($this->isMethod('post') || $this->isMethod('put')) {
            $rules = [
                'full_name' => 'required|string|max:255',
                'cpf'       => ['required', 'string', "unique:clients,cpf,{$clientId}", new ValidCpf],
                'email'     => ['required', 'email', "unique:clients,email,{$clientId}"],
                'phone'     => 'nullable|string|max:15',
                'cep'       => 'required|string|size:9'
            ];
        }
    
        return $rules;
    }
}
