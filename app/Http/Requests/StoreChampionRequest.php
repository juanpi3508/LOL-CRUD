<?php

namespace App\Http\Requests;

use App\Models\Champion;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChampionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100', 'unique:champions,name'],
            'title' => ['required', 'string', 'max:150'],
            'role' => ['required', 'string', Rule::in(array_keys(Champion::ROLES))],
            'resource_type' => ['required', 'string', Rule::in(Champion::RESOURCE_TYPES)],
            'difficulty' => ['required', 'string', Rule::in(Champion::DIFFICULTIES)],
            'lore' => ['required', 'string', 'min:10'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ];
    }

    /**
     * Mensajes de validación personalizados en español.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del campeón es obligatorio.',
            'name.unique' => 'Ya existe un campeón registrado con este nombre.',
            'title.required' => 'El título oficial es obligatorio (ej. El Segador de Almas).',
            'role.required' => 'Debes seleccionar un rol para el campeón.',
            'resource_type.required' => 'El tipo de recurso es obligatorio.',
            'difficulty.required' => 'Debes especificar el nivel de dificultad.',
            'lore.required' => 'La biografía o lore del campeón es obligatoria.',
            'lore.min' => 'El lore debe tener al menos 10 caracteres.',
            'image_url.url' => 'La URL del Splash Art debe ser un enlace válido.',
        ];
    }
}
