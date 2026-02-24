<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\DataIndividuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapAbsensiController;


// ============================================================
// AUTH
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/dosen', [AuthController::class, 'loginDosen'])->name('login.dosen');
Route::post('/login/mahasiswa', [AuthController::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// MAHASISWA — KRS (tidak perlu sidebar dosen)
// ============================================================
Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
Route::post('/krs/tambah', [KrsController::class, 'tambah'])->name('krs.tambah');
Route::delete('/krs/{id_krs}', [KrsController::class, 'hapus'])->name('krs.hapus');

// ============================================================
// REDIRECT ROOT
// ============================================================
Route::get('/', function () {
    return redirect('/dashboard');
});

// ============================================================
// DOSEN — DASHBOARD & SEMUA CRUD
// ============================================================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Dosen
Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store');
Route::put('/dosen/update/{id}', [DosenController::class, 'update'])->name('dosen.update');
Route::delete('/dosen/delete/{id}', [DosenController::class, 'destroy'])->name('dosen.delete');

// Mata Kuliah
Route::get('/mata-kuliah', [MataKuliahController::class, 'index']);
Route::post('/mata-kuliah', [MataKuliahController::class, 'store']);
Route::put('/mata-kuliah/{id}', [MataKuliahController::class, 'update']);
Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy']);

// Kelas
Route::resource('kelas', KelasController::class);
Route::get('/kelas/{id}/manage',[KelasController::class,'manage']);
Route::get('/kelas', [KelasController::class,'index'])->name('kelas.index');
Route::post('/kelas/store', [KelasController::class,'store'])->name('kelas.store');
Route::put('/kelas/update/{id}', [KelasController::class,'update'])->name('kelas.update');
Route::delete('/kelas/delete/{id}', [KelasController::class,'destroy'])->name('kelas.delete');

// Mahasiswa (admin/dosen CRUD)
Route::get('/mahasiswa', [DataIndividuController::class, 'index']);
Route::get('/mahasiswa/create', [DataIndividuController::class, 'create']);
Route::post('/mahasiswa', [DataIndividuController::class, 'store']);
Route::get('/mahasiswa/{id}/edit', [DataIndividuController::class, 'edit']);
Route::put('/mahasiswa/{id}', [DataIndividuController::class, 'update']);
Route::delete('/mahasiswa/{id}', [DataIndividuController::class, 'destroy']);

// ============================================================
// PERTEMUAN — urutan PENTING: /store sebelum /{id_kelas}
// ============================================================
Route::get('/pertemuan', [PertemuanController::class, 'index'])->name('pertemuan.index');
Route::post('/pertemuan/store', [PertemuanController::class, 'store'])->name('pertemuan.store');
Route::get('/pertemuan/{id_kelas}', [PertemuanController::class, 'show'])->name('pertemuan.show');
Route::put('/pertemuan/{id}', [PertemuanController::class, 'update'])->name('pertemuan.update');
Route::delete('/pertemuan/{id}', [PertemuanController::class, 'destroy'])->name('pertemuan.destroy');

// ============================================================
// ABSENSI
// ============================================================
Route::get('/absensi/{id_pertemuan}/data', [AbsensiController::class, 'getData'])->name('absensi.data');
Route::post('/absensi/update-status', [AbsensiController::class, 'updateStatus'])->name('absensi.updateStatus');

// ── Rekap Absensi ─────────────────────────────────────────────
Route::get('/absensi-rekap', [RekapAbsensiController::class, 'index'])->name('rekap.index');
Route::get('/absensi-rekap/export', [RekapAbsensiController::class, 'export'])->name('rekap.export');
