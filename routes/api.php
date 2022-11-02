<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\VerifyEmailController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/email-verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify');

//	->middleware(['auth', 'signed'])
//Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//	->middleware(['signed', 'throttle:6,1'])
//	->name('verification.verify');

Route::post('/login', [AuthController::class, 'login'])->name('login');
