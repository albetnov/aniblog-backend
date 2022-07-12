<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        if ($request->expectsJson()) {
            return response()->json(['message' => "Welcome", "data" => $request->user()], 200);
        }
        return $request->user();
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->middleware('permission:read category');
        Route::get('/categories/{id}', 'show')->middleware('permission:read category');
        Route::post('/categories', 'store')->middleware('permission:create category');
        Route::put('/categories/{id}', 'update')->middleware('permission:update category');
        Route::delete('/categories/{id}', 'destroy')->middleware('permission:delete category');
    });

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blogs', 'index')->middleware('permission:read blog');
        Route::get("/blogs/{id}", 'show')->middleware('permission:read blog');
        Route::post('/blogs', 'store')->middleware('permission:create blog');
        Route::put('/blogs/{id}', 'update')->middleware('permission:update blog');
        Route::delete('/blogs/{id}', 'destroy')->middleware('permission:delete blog');
    });

    Route::get('/users', [UserController::class, 'index'])->middleware('permission:read users|manage users');
    Route::apiResource('/users', UserController::class)->except('index')->middleware('permission:manage users');
    Route::apiResource('/roles', RoleController::class)->middleware('permission:manage roles');
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:manage roles');
});
