<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChampionRequest;
use App\Http\Requests\UpdateChampionRequest;
use App\Models\Ability;
use App\Models\Champion;
use App\Models\Skin;
use App\Services\RiotDataDragonService;
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
     * Muestra la ficha detallada de un campeón específico con sus aspectos y habilidades cargados.
     * Si no tiene datos asociados, se auto-sincronizan desde Riot Data Dragon de forma transparente.
     */
    public function show(Champion $champion, RiotDataDragonService $riotService): View
    {
        // Auto-sincronización en demanda si el campeón no tiene skins ni habilidades configuradas
        if ($champion->skins()->count() === 0 && $champion->abilities()->count() === 0) {
            $riotService->syncChampion($champion);
        }

        $champion->load(['skins', 'abilities']);
        $skinTiers = Skin::TIERS;
        $abilitySlots = Ability::SLOTS;

        return view('champions.show', compact('champion', 'skinTiers', 'abilitySlots'));
    }

    /**
     * Sincroniza manualmente las skins y habilidades oficiales del campeón con Riot Data Dragon.
     */
    public function sync(Champion $champion, RiotDataDragonService $riotService): RedirectResponse
    {
        $result = $riotService->syncChampion($champion);

        if (! $result['success']) {
            return redirect()
                ->route('champions.show', $champion)
                ->with('error', "No fue posible conectar con Riot Data Dragon para {$champion->name}.");
        }

        return redirect()
            ->route('champions.show', $champion)
            ->with('success', "¡Datos oficiales sincronizados! Se vincularon {$result['skins']} aspectos y {$result['abilities']} habilidades para {$champion->name}.");
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

    /**
     * Elimina un campeón de la base de datos de manera definitiva.
     */
    public function destroy(Champion $champion): RedirectResponse
    {
        $name = $champion->name;
        $title = $champion->title;
        $champion->delete();

        return redirect()
            ->route('champions.index')
            ->with('success', "¡El campeón {$name} ({$title}) ha sido eliminado de la Grieta del Invocador exitosamente!");
    }
}
