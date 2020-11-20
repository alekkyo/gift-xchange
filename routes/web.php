<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/exchange', 'ExchangeController@newExchange');
Route::get('/exchange/{code}', 'ExchangeController@showExchange');
Route::post('/exchange/{code}', 'ExchangeController@createExchangeUsers');
Route::get('/exchange/{code}/link/{link}', 'ExchangeController@viewPicked');
Route::get('/exchange/{code}/wishlist/{userId}', 'ExchangeController@viewWishlist');
Route::post('/exchange/{code}/wishlist/{userId}', 'ExchangeController@addWish');
Route::post('/exchange/{code}/wishlist/{userId}/delete-wish/{wishId}', 'ExchangeController@deleteWish');