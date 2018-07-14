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

/** Create user instance */

function user_init() {
    return new App\User;
}


/** Create Question instance */

function question_init() {
    return new App\Question;
}


/** Request all */
function rq($key=null, $default=null) {
    if (!$key) return Request::all();
    return Request::get($key, $default);
}



/** homepage */

Route::get('/', function () {
    return view('welcome');
});


/** Sign up page */

Route::any('api/signup', function() {
    return user_init()->signup();
});


/** Log in page */

Route::any('api/login', function() {
    return user_init()->login();
});


/** Log put page */

Route::any('api/logout', function() {
    return user_init()->logout();
});


/** Create question */

Route::any('api/question/create', function() {
    return question_init()->add();
});


/** Update question */

Route::any('api/question/change', function() {
    return question_init()->change();
});


/** Read question */

Route::any('api/question/read', function() {
    return question_init()->read();
});


/** Delete question */

Route::any('api/question/remove', function() {
    return question_init()->remove();
});


/** Hold for testing functionality */

Route::any('test', function() {
    dd(user_init()->is_logged_in());
});