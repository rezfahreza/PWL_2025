<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StokController;

Route::get('/', function () { //Membuat rute GET / yang menampilkan halaman welcome
    return view('welcome');
});

Route::resource('stoks', StokController::class);//Membuat resource route untuk StokController, yang menangani semua operasi CRUD untuk stoks