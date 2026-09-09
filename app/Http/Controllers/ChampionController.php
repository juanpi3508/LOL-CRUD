<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChampionRequest;
use App\Http\Requests\UpdateChampionRequest;
use App\Models\Champion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChampionController extends Controller
{
    /**
     * Catálogo de campeones con filtros por rol, dificultad y buscador por texto.
     */
    public function index(Request $request): View
    {
        $query = Champion::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('lore', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }

        $champions = $query->latest()->paginate(9)->withQueryString();
        $roles = Champion::ROLES;
        $difficulties = Champion::DIFFICULTIES;

        return view('champions.index', compact('champions', 'roles', 'difficulties'));
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

    /**
     * Muestra la ficha detallada de un campeón específico.
     */
    public function show(Champion $champion): View
    {
        return view('champions.show', compact('champion'));
    }

    /**
     * Muestra el formulario para editar los atributos de un campeón.
     */
    public function edit(Champion $champion): View
    {
        $roles = Champion::ROLES;
        $difficulties = Champion::DIFFICULTIES;
        $resourceTypes = Champion::RESOURCE_TYPES;

        return view('champions.edit', compact('champion', 'roles', 'difficulties', 'resourceTypes'));
    }

    /**
     * Actualiza los datos de un campeón en la base de datos.
     */
    public function update(UpdateChampionRequest $request, Champion $champion): RedirectResponse
    {
        $champion->update($request->validated());

        return redirect()
            ->route('champions.show', $champion)
            ->with('success', "¡El campeón {$champion->name} ({$champion->title}) ha sido actualizado con éxito!");
    }
}
