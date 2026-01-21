<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataIndividuController;

Route::get('/dataindividu', [DataIndividuController::class, 'index']);
Route::get('/dataindividu/create', [DataIndividuController::class, 'create']);
Route::post('/dataindividu', [DataIndividuController::class, 'store']);
Route::delete('/dataindividu/{nim}', [DataIndividuController::class, 'destroy']);
Route::get('/dataindividu/{nim}/edit', [DataIndividuController::class, 'edit']);
Route::put('/dataindividu/{nim}', [DataIndividuController::class, 'update']);

Route::get('/', function () {
    return view('welcome');
});
