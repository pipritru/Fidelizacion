<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
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
Route::resource('cities', CityController::class);
Route::resource('persons', PersonController::class);
Route::resource('roles', RoleController::class);   
Route::resource('permissions', PermissionController::class);
Route::resource('users', UsersController::class);
Route::post('users/register', [UserController::class, 'register']);
Route::post('users/login', [UserController::class, 'login']);

