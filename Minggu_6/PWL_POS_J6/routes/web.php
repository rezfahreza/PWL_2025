<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [WelcomeController::class, 'index']);
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          // Menampilkan halaman utama pengguna
    Route::post('/list', [UserController::class, 'list']);      // Menampilkan data pengguna dalam format JSON untuk datatables
    Route::get('/create', [UserController::class, 'create']);   // Menampilkan form untuk tambah pengguna
    Route::post('/', [UserController::class, 'store']);         // Menyimpan data pengguna baru
    Route::get('/{id}', [UserController::class, 'show']);       // Menampilkan detail pengguna
    Route::get('/{id}/edit', [UserController::class, 'edit']);  // Menampilkan form untuk edit pengguna
    Route::put('/{id}', [UserController::class, 'update']);     // Menyimpan perubahan data pengguna
    Route::delete('/{id}', [UserController::class, 'destroy']); // Menghapus data pengguna
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);             
    Route::post('/list', [LevelController::class, 'list']);       
    Route::get('/create', [LevelController::class, 'create']);    
    Route::post('/', [LevelController::class, 'store']);          
    Route::get('/{id}', [LevelController::class, 'show']);        
    Route::get('/{id}/edit', [LevelController::class, 'edit']); 
    Route::put("/{id}", [LevelController::class, 'update']);       
    Route::delete('/{id}', [LevelController::class, 'destroy']);  
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']);
    Route::post('/list', [KategoriController::class, 'list']);
    Route::get('/create', [KategoriController::class, 'create']);
    Route::post('/', [KategoriController::class, 'store']);
    Route::get('/{id}', [KategoriController::class, 'show']);
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);
    Route::put("/{id}", [KategoriController::class, 'update']);
    Route::delete('/{id}', [KategoriController::class, 'destroy']);
});

Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::post('/list', [SupplierController::class, 'list']);
    Route::get('/create', [SupplierController::class, 'create']);
    Route::post('/', [SupplierController::class, 'store']);
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::get('/{id}/edit', [SupplierController::class, 'edit']);
    Route::put("/{id}", [SupplierController::class, 'update']);
    Route::delete('/{id}', [SupplierController::class, 'destroy']);
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']);
    Route::post('/list', [BarangController::class, 'list']);
    Route::get('/create', [BarangController::class, 'create']);
    Route::post('/', [BarangController::class, 'store']);
    Route::get('/{id}', [BarangController::class, 'show']);
    Route::get('/{id}/edit', [BarangController::class, 'edit']);
    Route::put("/{id}", [BarangController::class, 'update']);
    Route::delete('/{id}', [BarangController::class, 'destroy']);
});