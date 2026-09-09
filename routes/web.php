<?php

use App\Http\Controllers\ChampionController;
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

