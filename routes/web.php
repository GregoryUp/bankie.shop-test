<?php

use App\Http\Controllers\ImageController;
use App\Models\Image;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/list', [ImageController::class, 'all']);
Route::post('/upload', [ImageController::class, 'upload']);
Route::get('/preview', [ImageController::class, 'preview']);
Route::get('/download', [ImageController::class, 'download']);