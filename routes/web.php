<?php

use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return  redirect()->route('dashboard');
});
Route::get('/phpinfo', fn() => phpinfo());


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/rules', [RuleController::class, 'index'])->name('rules.index');
    Route::get('/rules/create', [RuleController::class, 'create'])->name('rules.create');
    Route::post('/rules', [RuleController::class, 'store'])->name('rules.store');
    Route::get('/rules/{id}/edit', [RuleController::class, 'edit'])->name('rules.edit');
    Route::put('/rules/{id}', [RuleController::class, 'update'])->name('rules.update');
    Route::delete('/rules/{id}', [RuleController::class, 'destroy'])->name('rules.destroy');
});

require __DIR__ . '/auth.php';
