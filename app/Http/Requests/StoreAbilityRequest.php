<?php

namespace App\Http\Requests;

use App\Models\Ability;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAbilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slot' => ['required', 'string', Rule::in(array_keys(Ability::SLOTS))],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:5'],
            'icon_url' => ['nullable', 'url', 'max:500'],
            'cooldown' => ['nullable', 'string', 'max:50'],
            'cost' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Mensajes de validación personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slot.required' => 'Debes asignar una tecla o ranura para la habilidad (P, Q, W, E o R).',
            'slot.in' => 'La tecla o ranura seleccionada no es válida.',
            'name.required' => 'El nombre de la habilidad es obligatorio.',
            'name.max' => 'El nombre de la habilidad no puede superar los 120 caracteres.',
            'description.required' => 'La descripción de los efectos de la habilidad es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 5 caracteres.',
            'icon_url.url' => 'La URL del icono debe ser un enlace web válido.',
        ];
    }
}
