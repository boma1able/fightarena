<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\BattleHistoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ForgeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');
Route::get('/inventory', [InventoryController::class, 'index'])->middleware('auth')->name('inventory');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/battle', [BattleController::class, 'index'])->name('battle');
    Route::get('/battle/stats', [BattleController::class, 'stats'])->name('battle.stats');
});

Route::get('/history', [BattleHistoryController::class, 'index'])->name('history');
Route::middleware(['auth'])->get('/forge', [ForgeController::class, 'index'])->name('forge');
