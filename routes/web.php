<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // autres routes...
});

// **************************************annonce****************************************************

use App\Http\Controllers\AnnonceController;

Route::middleware(['auth', 'verified'])->group(function () {
    // ... autres routes
    Route::get('/annonce/create', [AnnonceController::class, 'create'])->name('annonce.create');
    Route::post('/annonce', [AnnonceController::class, 'store'])->name('annonce.store');
});

// *****************************************************************************************************

// *******************************public***********************************

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/a-propos', [PublicController::class, 'about'])->name('about');
Route::get('/fonctionnalites', [PublicController::class, 'features'])->name('features');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('contact.submit');

// ******************************************************************************************

