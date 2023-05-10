<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\V1\Controllers')->group(function () {
    Route::permanentRedirect('/', '/api/v1/status');

    Route::middleware('guest')->group(function () {
        Route::get('status', 'Status@index')
            ->name('status');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', 'Me@show');
    });
});
