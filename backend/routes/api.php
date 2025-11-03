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
use App\Http\Controllers\RedemptionController;



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

// Public routes (no auth required)
Route::post('users/register', [UserController::class, 'register']);
Route::post('users/login', [UserController::class, 'login']);

// Routes that require authentication for both admin and clients
Route::middleware(['auth:sanctum'])->group(function () {
    // Users: authenticated users can view and edit their profile (index/list is admin-only)
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);

    // Orders: both can view and create orders; update allowed (business rules may restrict state changes)
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'store', 'update']);

    // Persons: both can manage persons (create, update, delete, view) — consider Policies for ownership
    Route::resource('persons', PersonController::class);

    // Simple debug endpoint: return the authenticated user and its role
    Route::get('me', function (Request $request) {
        $user = $request->user();
        if (method_exists($user, 'role')) {
            $user->load('role');
        }
        return response()->json($user);
    });

    // Permisos efectivos del usuario autenticado
    Route::get('me/permissions', [UserController::class, 'myPermissions']);
    
    // Points endpoints
    Route::get('users/{id}/points', [\App\Http\Controllers\PointsController::class, 'show']);
    Route::post('redeem', [\App\Http\Controllers\PointsController::class, 'redeem']);
    // Redemption of points for products
    Route::post('redemptions', [RedemptionController::class, 'store']);
});

// Admin-only routes: require authentication and admin role
Route::group(['middleware' => ['auth:sanctum', \App\Http\Middleware\CheckRole::class . ':administrador']], function() {
    // Admin manages cities, states, roles, permissions and full users management
    Route::resource('cities', CityController::class);
    Route::resource('states', StateController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Admin users management (including list, delete, adjust points)
    Route::get('users', [UserController::class, 'index']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('users/{id}/points', [UserController::class, 'adjustPoints']);
    Route::get('reports/points', [UserController::class, 'pointsReport']);

    // Products: admin + permiso por bandera (CRUD)
    Route::post('products', [ProductController::class, 'store'])->middleware('permission:products,create');
    Route::put('products/{id}', [ProductController::class, 'update'])->middleware('permission:products,edit');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->middleware('permission:products,delete');
    Route::patch('products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->middleware('permission:products,edit');
});

// Productos públicos (lectura): si quieres forzar permiso de lectura, añade middleware('permission:products,view')
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
