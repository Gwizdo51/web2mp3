<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/submit', [MainController::class, 'submitForm']);

Route::get('/status/{id}', [MainController::class, 'getDownloadStatus'])
    ->where('id', '[0-9a-zA-Z]+');
