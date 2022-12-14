<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\QuoteController;
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

Route::middleware('jwt.auth')->group(function () {
	Route::controller(AuthController::class)->group(function () {
		Route::get('/user', 'user')->name('auth.user');
		Route::post('/logout', 'logout')->name('logout');
		Route::post('/update-profile', 'update')->name('user.update');
		Route::post('/add-email', 'addEmail')->name('user.add_email');
		Route::post('/make-primary-email/{email}', 'makePrimaryEmail')->name('user.make_primary_email');
		Route::delete('/delete-email/{email}', 'deleteEmail')->name('user.delete_email');
	});

	Route::controller(MovieController::class)->group(function () {
		Route::get('/movies', 'index')->name('movie.index');
		Route::post('/movies', 'store')->name('movie.store');
		Route::post('/search-movies', 'search')->name('movie.search');
		Route::get('/movies/{movie}', 'show')->name('movie.show');
		Route::post('/movies/{movie}', 'update')->name('movie.update');
		Route::delete('/movies/{movie}', 'destroy')->name('movie.destroy');
		Route::get('/genres', 'getGenres')->name('movie.genres');
	});

	Route::controller(QuoteController::class)->group(function () {
		Route::get('/quotes', 'index')->name('quotes.index');
		Route::post('/quotes', 'store')->name('quote.store');
		Route::post('/search-quotes', 'search')->name('quote.search');
		Route::get('/quotes/{quote}', 'show')->name('quote.show');
		Route::post('/quotes/{quote}', 'update')->name('quote.update');
		Route::delete('/quotes/{quote}', 'destroy')->name('quote.destroy');
	});

	Route::controller(CommentController::class)->group(function () {
		Route::get('/quotes/{quote}/comments', 'index')->name('comment.index');
		Route::post('/quotes/{quote}/comments', 'store')->name('comment.store');
	});

	Route::controller(LikeController::class)->group(function () {
		Route::get('/get-likes/{quote}', 'getLikes')->name('likes.get');
		Route::get('/check-likes/{quote}', 'checkLikes')->name('likes.check');
		Route::post('/like-quote/{quote}', 'like')->name('like.quote');
		Route::post('/unlike-quote/{quote}', 'unlike')->name('unlike.quote');
	});
});

Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::get('/email-verify/{id}/{hash}', 'verify')->name('verification.verify');
	Route::post('/login', 'login')->name('login');
});

Route::post('/forgot-password', [PasswordResetRequestController::class, 'sendEmail'])->name('forgot.password');
Route::post('/reset-password', [ChangePasswordController::class, 'passwordReset'])->name('reset.password');

Route::get('/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
