<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KegiatanDosenController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\KonfirmasiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// dd(bcrypt("password"));
Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/admin', [HomeController::class, 'admin'])->middleware('role:admin');
Route::get('/user', [HomeController::class, 'user'])->middleware('role:user');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

    //kegiatan
    Route::get('/tambahKegiatan', [App\Http\Controllers\KegiatanDosenController::class, 'create'])->name('tambahKegiatan');
    Route::get('/kegiatanDosen', [App\Http\Controllers\KegiatanDosenController::class, 'index'])->name('kegiatanDosen.index');
    Route::post('/simpanKegiatan', [App\Http\Controllers\KegiatanDosenController::class, 'store'])->name('simpanKegiatan');
    Route::get('/editKegiatan/{id}', [KegiatanDosenController::class, 'edit'])->name('editKegiatan');
    Route::post('/updateKegiatan/{id}', [KegiatanDosenController::class, 'update'])->name('updateKegiatan');
    Route::get('/deleteKegiatan/{id}', [KegiatanDosenController::class, 'destroy'])->name('deleteKegiatan');
    Route::get('/searchKegiatan', [KegiatanDosenController::class, 'search'])->name('searchKegiatan');

    //Dosen
    Route::get('/dosen', [App\Http\Controllers\DosenController::class, 'index'])->name('dosen.index');
    Route::get('/tambahDosen', [App\Http\Controllers\DosenController::class, 'create'])->name('tambahDosen');
    Route::post('/simpanDosen', [App\Http\Controllers\DosenController::class, 'store'])->name('simpanDosen');
    Route::get('/editDosen/{NIP}', [DosenController::class, 'edit'])->name('editDosen');
    Route::post('/updateDosen/{NIP}', [DosenController::class, 'update'])->name('updateDosen');
    Route::get('/deleteDosen/{NIP}', [DosenController::class, 'destroy'])->name('deleteDosen');
    Route::get('/searchDosen', [DosenController::class, 'search'])->name('searchDosen');
   
    //logout
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

    //jadwal
    Route::get('/jadwal', [App\Http\Controllers\JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/tambahJadwal', [App\Http\Controllers\JadwalController::class, 'create'])->name('tambahJadwal');
    Route::post('/simpanJadwal', [App\Http\Controllers\JadwalController::class, 'store'])->name('simpanJadwal');
    Route::get('/editJadwal/{id}', [JadwalController::class, 'edit'])->name('editJadwal');
    Route::post('/updateJadwal/{id}', [JadwalController::class, 'update'])->name('updateJadwal');
    Route::get('/deleteJadwal/{id}', [JadwalController::class, 'destroy'])->name('deleteJadwal');
    // Route::get('/searchKegiatan', [KegiatanDosenController::class, 'search'])->name('searchKegiatan');

    Route::get('/konfirmasi', [App\Http\Controllers\KonfirmasiController::class, 'index'])->name('konfirmasi.index');
    Route::post('/konfirmasi-kehadiran/{id}', [KonfirmasiController::class, 'konfirmasiKehadiran'])->name('konfirmasi.kehadiran'); 
    Route::post('/konfirmasi-kehadiran-upload', [KonfirmasiController::class, 'uploadBuktiKehadiran'])->name('konfirmasi.upload');
});

//Login with google
Route::get('auth/google', [App\Http\Controllers\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [App\Http\Controllers\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

