<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

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

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

Route::get('/task-list', function () {
    return view('tasks'); // Renders resources/views/tasks/index.blade.php
});

Route::get('/register', function () {
    return view('register'); // Renders resources/views/tasks/index.blade.php
});

Route::get('/login', function () {
    return view('login'); // Renders resources/views/tasks/index.blade.php
});

