<?php

namespace App\Http\Requests;

use App\Models\Skin;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkinRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'tier' => ['required', 'string', Rule::in(array_keys(Skin::TIERS))],
            'price_rp' => ['required', 'integer', 'min:0', 'max:99999'],
            'splash_art_url' => ['nullable', 'url', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
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
            'name.required' => 'El nombre del aspecto es obligatorio.',
            'name.max' => 'El nombre del aspecto no puede superar los 120 caracteres.',
            'tier.required' => 'Debes seleccionar una categoría o rareza para el aspecto.',
            'tier.in' => 'La categoría de aspecto seleccionada no es válida.',
            'price_rp.required' => 'El precio en Riot Points (RP) es obligatorio.',
            'price_rp.integer' => 'El precio en RP debe ser un valor numérico entero.',
            'price_rp.min' => 'El precio en RP no puede ser negativo.',
            'splash_art_url.url' => 'La URL del Splash Art del aspecto debe ser un enlace web válido.',
        ];
    }
}
