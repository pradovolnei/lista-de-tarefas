<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Permite que qualquer usuário faça a requisição
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255', // Obrigatório, string
            'description' => 'nullable|string', // Opcional, string
            'is_completed' => 'sometimes|boolean', // Opcional para POST, mas presente no PUT/PATCH
        ];

        // Para o método PATCH (marcar como concluída/pendente)
        if ($this->isMethod('PATCH') || $this->routeIs('api.tasks.mark')) {
            // Apenas 'is_completed' é necessário/validado.
            return ['is_completed' => 'required|boolean'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O campo Título é obrigatório.',
            'title.max' => 'O Título não pode ter mais que 255 caracteres.',
            'is_completed.boolean' => 'O status Concluída deve ser um valor booleano (true/false).',
            'is_completed.required' => 'O status Concluída é obrigatório para esta operação.',
        ];
    }
}
