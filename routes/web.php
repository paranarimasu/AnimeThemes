<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'do'])->name('welcome');

Route::get('/sitemap', [SitemapController::class, 'index']);
Route::get('/sitemap/videos', [SitemapController::class, 'videos'])->name('video_sitemap');

Route::resource('video', VideoController::class)->only(['show']);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
