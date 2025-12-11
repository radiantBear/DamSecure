<?php

use App\Http\Controllers;
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

Route::get('/login', [Controllers\UserController::class, 'login'])->name('login');
Route::get('/authenticate', [Controllers\UserController::class, 'authenticate']);

Route::view('/docs/api', 'api');

Route::middleware('auth')->group(function () {
    Route::resource('projects', Controllers\ProjectController::class);
    Route::get('/projects/{project}/tokens', [Controllers\ProjectController::class, 'indexTokens']);
    Route::put('/projects/{project}/tokens/{scope}', [Controllers\ProjectController::class, 'rotateToken']);

    Route::controller(Controllers\ProjectUserController::class)->group(function () {
        Route::get('/projects/{project}/permissions', 'index');
        Route::post('/projects/{project}/permissions', 'store');
        Route::patch('/permissions/{project_user}', 'update');
        Route::delete('/permissions/{project_user}', 'destroy');
    });

    Route::get('/logout', [Controllers\UserController::class, 'logout'])->name('logout');

    Route::delete('/data/{data}', [Controllers\DataController::class, 'destroy']);
});
