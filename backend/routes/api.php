<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\PersonsController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Ruta de ejemplo para verificar la autenticación (opcional)
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de recursos para los controladores
Route::resource('states', StateController::class);
Route::resource('cities', CitiesController::class);
Route::resource('persons', PersonsController::class);
Route::resource('users', UsersController::class);