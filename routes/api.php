<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

Route::get('media', [MediaController::class, 'index']);
Route::post('media', [MediaController::class, 'store']);
Route::get('media/{media}', [MediaController::class, 'show']);
Route::patch('media/{media}', [MediaController::class, 'update']);
Route::delete('media/{media}', [MediaController::class, 'destroy']);
Route::get('penggunas', [PenggunaController::class, 'index']);
Route::post('penggunas', [PenggunaController::class, 'store']);
Route::get('penggunas/{pengguna}', [PenggunaController::class, 'show']);
Route::post('penggunas/{pengguna}/media/{media}', [PenggunaController::class, 'attach']);
Route::delete('penggunas/{pengguna}/media/{media}', [PenggunaController::class, 'detach']);
Route::post('peminjaman', [PeminjamanController::class, 'store']);
Route::patch('peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'returnMedia']);
