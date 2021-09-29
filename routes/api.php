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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login_zalo', 'ZaloAuthController@loginZalo');

Route::group([
    'middleware' => 'zalo.auth',
    'prefix' => 'zalo'

], function ($router) {
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refesh');
    Route::get('/user-profile', 'ZaloAuthController@userProfile');    
    Route::get('products/by-category', 'Api\ProductController@getGroupProducts');
    Route::post('orders/checkout', 'Api\OrderController@checkout');
    Route::get('orders/history', 'Api\OrderController@getHistory');
});


Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::group([
    'middleware' => 'api.auth',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refesh');
    Route::get('/user-profile', 'AuthController@userProfile');    
    Route::get('categories', 'Api\CategoryController@index');
    Route::get('products', 'Api\ProductController@index');
    Route::get('manufacturers', 'Api\ManufacturerController@index');
    Route::get('prices', 'Api\PriceController@index');  
});