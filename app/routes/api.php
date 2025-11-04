<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;
Route::middleware(['apikey'])->group(function () {
    Route::post('/links', [LinkController::class, 'store']);
    Route::get('/links/{slug}/stats', [LinkController::class, 'stats']);
});