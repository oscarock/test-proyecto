<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register'])->withoutMiddleware('custom.jwt');

Route::middleware('custom.jwt')->group(function () {
    Route::apiResource('tasks', TaskController::class)->except([
        'create', 'edit'
    ]);
});