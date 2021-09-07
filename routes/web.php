<?php

use Ajax\JwtRedis\Facades\JwtRedis;
use Illuminate\Support\Facades\Redis;
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

Route::view('/', 'home');

Route::view('/livewire', 'livewire');

Route::get('/', function(){
    $params = [
        '0906886627', //imei
        '123456' //user_id
    ]; 

    $redis = JwtRedis::getToken($params);

    dd($redis);
});
