<?php
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

route::get('/level',action: [LevelController::class, 'index']);
route::get('/kategori',action: [KategoriController::class, 'index']);
route::get('/user',action: [UserController::class, 'index']);
