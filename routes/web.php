<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (config('app.env') !== "production") {
    Route::view('/', 'index');
} else {
    Route::get('/', function () {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Welcome to AniBlog Backend endpoint.']);
        exit;
    });
}

Route::get("/password-reset", function () {
    echo "This is password reset!";
})->name("password.reset");
