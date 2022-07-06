<?php

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
    Route::get("/admin", function () {
        return "Hello Admin!";
    })->middleware('role_or_permission:admin|admin dashboard');
    Route::get("/user/dashboard", function () {
        return "Hello User!";
    })->middleware('role_or_permission:user|user dashboard');
});
