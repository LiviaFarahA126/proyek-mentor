<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataIndividuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\AbsensiController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (NO LOGIN DULU)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard',[DashboardController::class,'index'])
    ->name('dashboard');

Route::get('/mata-kuliah',[MataKuliahController::class,'index']);

Route::get('/mata-kuliah',[MataKuliahController::class,'index']);
Route::post('/mata-kuliah',[MataKuliahController::class,'store']);
Route::put('/mata-kuliah/{id}',[MataKuliahController::class,'update']);
Route::delete('/mata-kuliah/{id}',[MataKuliahController::class,'destroy']);


Route::get('/kelas/{id}',
    [KelasController::class,'show']);

Route::get('/kelas/{id}/pertemuan/{pertemuan_id}',
    [PertemuanController::class,'show']);

Route::post('/absensi',
    [AbsensiController::class,'store']);

Route::get('/mahasiswa',[DataIndividuController::class,'index']);
Route::get('/mahasiswa/create',[DataIndividuController::class,'create']);
Route::post('/mahasiswa',[DataIndividuController::class,'store']);
Route::get('/mahasiswa/{id}/edit',[DataIndividuController::class,'edit']);
Route::put('/mahasiswa/{id}',[DataIndividuController::class,'update']);
Route::delete('/mahasiswa/{id}',[DataIndividuController::class,'destroy']);
    