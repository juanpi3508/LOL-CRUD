<?php

use App\Http\Controllers\ChampionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('champions.create');
});

Route::get('/champions', [ChampionController::class, 'index'])->name('champions.index');
Route::get('/champions/create', [ChampionController::class, 'create'])->name('champions.create');
Route::post('/champions', [ChampionController::class, 'store'])->name('champions.store');

