<?php

use App\Http\Controllers\Api\ImageApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/images', [ImageApiController::class, 'all']);
Route::get('/images/{id}', [ImageApiController::class, 'get']);
