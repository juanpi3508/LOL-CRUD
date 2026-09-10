<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbilityRequest;
use App\Models\Ability;
use App\Models\Champion;
use Illuminate\Http\RedirectResponse;

class AbilityController extends Controller
{
    /**
     * Almacena una nueva habilidad asignada a un campeón.
     */
    public function store(StoreAbilityRequest $request, Champion $champion): RedirectResponse
    {
        $ability = $champion->abilities()->create($request->validated());

        return redirect()
            ->route('champions.show', $champion)
            ->with('success', "¡Habilidad [{$ability->slot}] '{$ability->name}' vinculada a {$champion->name} exitosamente!");
    }

    /**
     * Elimina una habilidad específica del arsenal del campeón.
     */
    public function destroy(Ability $ability): RedirectResponse
    {
        $champion = $ability->champion;
        $slot = $ability->slot;
        $name = $ability->name;
        $ability->delete();

        return redirect()
            ->route('champions.show', $champion)
            ->with('success', "¡Habilidad [{$slot}] '{$name}' retirada exitosamente!");
    }
}
