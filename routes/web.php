<?php
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

route::get('/level',action: [LevelController::class, 'index']);
route::get('/kategori',action: [KategoriController::class, 'index']);
