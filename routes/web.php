<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, PostController};

Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home.page');
    })->name('home');
    Route::get('/calendar', function () {
        return view('calendar.page');
    })->name('calender');

    Route::prefix('post')->group(function () {
        // Halaman utama daftar post
        Route::get('/', [PostController::class, 'index'])->name('post.index');
        
        // Halaman form tambah post
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        
        // Simpan data post baru
        Route::post('/', [PostController::class, 'store'])->name('post.store');
        
        // Halaman edit post
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
        
        // Update post
        Route::put('/{id}', [PostController::class, 'update'])->name('post.update');
        
        // Hapus post
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('post.destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});