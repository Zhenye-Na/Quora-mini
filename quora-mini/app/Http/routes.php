<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

function user_init() {
    return $user = new App\User;
}


Route::get('/', function () {
    return view('welcome');
});


Route::any('api/signup', function() {
    return user_init()->signup();
});


Route::any('api/login', function() {
    return user_init()->login();
});