<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenggunaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::prefix('admin')->group(function () {
//     //ADMIN AUTH ----------------------
//     Route::post('/register', 'AdminController@create');
//     Route::post('/login', 'AdminController@login');
//     Route::post('/edit/{id}', 'AdminController@update');
//     Route::get('/del-auth/{id}', 'AdminController@destroy');
//     Route::get('/show/{id}', 'AdminController@show');
//     Route::get('/index', 'AdminController@index');
//     Route::get('/ubah-status', 'AdminController@ubahStatusLaporan');


//     //ADMIN KATEGORI ---------------------
//     Route::prefix('kategori')->group(function () {
//         Route::get('/index', 'KategoriController@index');
//         Route::post('/add', 'KategoriController@create');
//         Route::post('/edit/{id}', 'KategoriController@update');
//         Route::get('/del/{id}', 'KategoriController@destroy');
//         Route::get('/show/{id}', 'KategoriController@show');
//     });
// });

//ADMIN AUTH ----------------------
Route::post('admin/register', [AdminController::class, 'create']);
Route::post('admin/login', [AdminController::class, 'login']);
Route::post('admin/edit/{id}', [AdminController::class, 'update']);
Route::get('admin/del-auth/{id}', [AdminController::class, 'destroy']);
Route::get('admin/show/{id}', [AdminController::class, 'show']);
Route::get('admin/index', [AdminController::class, 'index']);
Route::get('admin/ubah_status/{id}', [AdminController::class, 'ubahStatusLaporan']);

//ADMIN KATEGORI ---------------------
// Route::get('admin/kategori/index', [KategoriController::class, 'index']);
// Route::post('admin/kategori/add', [KategoriController::class, 'create']);
// Route::get('admin/kategori/edit/{id}', [KategoriController::class, 'update']);
// Route::get('admin/kategori/del/{id}', [KategoriController::class, 'destroy']);
// Route::get('admin/kategori/index/show{id}', [KategoriController::class, 'show']);

//PENGGUNA AUTH
Route::post('pengguna/register', [PenggunaController::class, 'create'])->name('register');
Route::post('pengguna/login', [PenggunaController::class, 'login'])->name('login');
Route::post('pengguna/edit/{id}', [PenggunaController::class, 'update']);
Route::post('pengguna/forgot-password', [PenggunaController::class, 'forgot']);
Route::get('pengguna/index', [PenggunaController::class, 'index']);
Route::get('pengguna/show/{id}', [PenggunaController::class, 'show']);
Route::get('pengguna/del-auth/{id}', [PenggunaController::class, 'destroy']);

//MEMBUAT LAPORAN -------------------------------
Route::post('pengguna/laporan/add', [LaporanController::class, 'buatLaporan']);
Route::get('pengguna/laporan/index', [LaporanController::class, 'index']);
Route::get('admin/laporan/index', [LaporanController::class, 'index']);

//KATEGORI ----------------------------------------
Route::post('kategori/edit/{id}', [KategoriController::class, 'update']);
Route::get('kategori/del-auth/{id}', [KategoriController::class, 'destroy']);
Route::get('kategori/show/{id}', [KategoriController::class, 'show']);
Route::get('kategori/index', [KategoriController::class, 'index']);
Route::post('kategori/create', [KategoriController::class, 'create']);


// Route::prefix('kelas')->group(function(){
//     //KELAS
//     Route::post('/edit/{id}', [KelasController::class, 'update']);
//     Route::get('/del-auth/{id}', [KelasController::class, 'destroy']);
//     Route::get('/show/{id}', [KelasController::class, 'show']);
//     Route::get('/index', [KelasController::class, 'index']);
//     Route::post('/register', [KelasController::class, 'register']);
// });

// Route::prefix('pengguna')->group(function () {
//     //PENGGUNA AUTH -------------------------
//     Route::post('pengguna/register', 'PenggunaController@create')->name('register');
//     Route::post('/login', 'PenggunaController@login')->name('login');
//     Route::post('/edit/{id}', 'PenggunaController@update');
//     Route::post('/forgot-password', 'PenggunaController@forgot');
//     Route::post('/change-profile-picture/{id}', 'PenggunaController@updateGambar');
//     Route::get('/index', 'PenggunaController@index');
//     Route::get('/show/{id}', 'PenggunaController@show');
//     Route::get('/del-auth/{id}', 'PenggunaController@destroy');

//     //MEMBUAT LAPORAN -------------------------------
//     Route::prefix('laporan')->group(function () {
//         Route::post('laporan/add', 'LaporanController@buatLaporan');
//         Route::get('index', 'LaporanController@index');
//     });
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
