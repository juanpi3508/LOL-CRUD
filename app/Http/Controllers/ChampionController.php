<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChampionRequest;
use App\Models\Champion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChampionController extends Controller
{
    /**
     * Catálogo de campeones (temporalmente redirige al formulario de creación o listado).
     */
    public function index(): View|RedirectResponse
    {
        $champions = Champion::latest()->get();
        return view('champions.index', compact('champions'));
    }

    /**
     * Muestra el formulario para crear un nuevo campeón.
     */
    public function create(): View
    {
        $roles = Champion::ROLES;
        $difficulties = Champion::DIFFICULTIES;
        $resourceTypes = Champion::RESOURCE_TYPES;

        return view('champions.create', compact('roles', 'difficulties', 'resourceTypes'));
    }

    /**
     * Almacena un campeón recién creado en la base de datos.
     */
    public function store(StoreChampionRequest $request): RedirectResponse
    {
        $champion = Champion::create($request->validated());

        return redirect()
            ->route('champions.create')
            ->with('success', "¡El campeón {$champion->name} ({$champion->title}) ha sido invocado exitosamente!");
    }
}
