<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;



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
Route::resource('users', UserController::class);
Route::post('users/register', [UserController::class, 'register']);
Route::post('users/login', [UserController::class, 'login']);
Route::resource('orders', OrderController::class);
Route::resource('products', ProductController::class);
Route::Patch ('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus']);

// Admin-only routes: require authentication and admin role
Route::group(['middleware' => ['auth:sanctum', 'role:admin'], 'prefix' => 'admin'], function() {
    // Example: adjust points for a user
    Route::post('users/{id}/points', [UserController::class, 'adjustPoints']);
    // Reports
    Route::get('reports/points', [UserController::class, 'pointsReport']);
    // Protect product creation/update/delete through admin
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
    Route::patch('products/{id}/toggle-status', [ProductController::class, 'toggleStatus']);
});


