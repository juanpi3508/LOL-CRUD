<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\ChampionController;
use App\Http\Controllers\SkinController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('champions.index');
});

Route::get('/champions', [ChampionController::class, 'index'])->name('champions.index');
Route::get('/champions/create', [ChampionController::class, 'create'])->name('champions.create');
Route::post('/champions', [ChampionController::class, 'store'])->name('champions.store');
Route::get('/champions/{champion}', [ChampionController::class, 'show'])->name('champions.show');
Route::get('/champions/{champion}/edit', [ChampionController::class, 'edit'])->name('champions.edit');
Route::put('/champions/{champion}', [ChampionController::class, 'update'])->name('champions.update');
Route::delete('/champions/{champion}', [ChampionController::class, 'destroy'])->name('champions.destroy');

// Rutas de Aspectos / Skins
Route::post('/champions/{champion}/skins', [SkinController::class, 'store'])->name('champions.skins.store');
Route::delete('/skins/{skin}', [SkinController::class, 'destroy'])->name('skins.destroy');

// Rutas de Habilidades / Abilities
Route::post('/champions/{champion}/abilities', [AbilityController::class, 'store'])->name('champions.abilities.store');
Route::delete('/abilities/{ability}', [AbilityController::class, 'destroy'])->name('abilities.destroy');

// Sincronización con Riot Data Dragon
Route::post('/champions/{champion}/sync', [ChampionController::class, 'sync'])->name('champions.sync');
