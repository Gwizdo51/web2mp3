<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\MainController;


// Route::get('/', function () {
//     return Inertia::render('Landing');
// })->name('home');
Route::get('/', [MainController::class, 'getLandingPage'])
    ->name('home');

Route::post('/', [MainController::class, 'newDownloadRequest']);

// Route::get('/test', function () {
//     return Inertia::render('Test', ['data' => [
//         'field1' => 'prout',
//         'field2' => 'caca',
//     ]]);
// });

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
