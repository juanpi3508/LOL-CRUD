<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkinRequest;
use App\Models\Champion;
use App\Models\Skin;
use Illuminate\Http\RedirectResponse;

class SkinController extends Controller
{
    /**
     * Almacena una nueva skin asociada a un campeón en la base de datos.
     */
    public function store(StoreSkinRequest $request, Champion $champion): RedirectResponse
    {
        $skin = $champion->skins()->create($request->validated());

        return redirect()
            ->route('champions.show', $champion)
            ->with('success', "¡El aspecto '{$skin->name}' ha sido forjado para {$champion->name} exitosamente!");
    }

    /**
     * Elimina un aspecto específico de la base de datos.
     */
    public function destroy(Skin $skin): RedirectResponse
    {
        $champion = $skin->champion;
        $skinName = $skin->name;
        $skin->delete();

        return redirect()
            ->route('champions.show', $champion)
            ->with('success', "¡El aspecto '{$skinName}' ha sido retirado del inventario exitosamente!");
    }
}
