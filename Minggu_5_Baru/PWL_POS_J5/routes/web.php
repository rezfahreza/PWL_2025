<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;

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