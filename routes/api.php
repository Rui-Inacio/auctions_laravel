<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserWalletController;
use App\Http\Controllers\AuctionsController;

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

if (App::environment('production')) {
    URL::forceScheme('https');
}

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['guest'])->post('/register', 'UsersController@register');
Route::middleware(['guest'])->post('/login', 'UsersController@login');
Route::get('/searchAuction', 'AuctionsController@searchAuction');
Route::post('/forgotPassword', 'UsersController@forgotPassword');
Route::get('/auctions', 'AuctionsController@index');
Route::post('/logout', 'UsersController@logout');

// Make sure only authenticated users can bid and close auctions

Route::middleware('auth:api')->group(function () {

    Route::prefix('/auction')->group(function () {
        Route::put('{id}', 'AuctionsController@bidAuction');
        Route::put('/{id}/close', 'AuctionsController@closeAuction');
        Route::post('/', 'AuctionsController@store');
        Route::get('/{email}', 'AuctionsController@userAuctions');
    });

    Route::prefix('/wallet')->group(function () {
        Route::get('/', 'UserWalletController@getBalance');
        Route::put('/deposit', 'UserWalletController@deposit');
        Route::put('/withdraw', 'UserWalletController@withdraw');
        Route::put('/transfer', 'UserWalletController@transfer');
    });

});

