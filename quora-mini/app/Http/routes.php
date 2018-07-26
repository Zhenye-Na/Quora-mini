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


/** Create User instance */

function user_init() {
    return new App\User;
}


/** Create Question instance */

function question_init() {
    return new App\Question;
}


/** Create Comment instance */

function comment_init() {
    return new App\Comment;
}


/** Create Answer instance */

function answer_init() {
    return new App\Answer;
}


/** Paginate wrapper 
 * @param $page: skip length
 * @param $limit: number of questions/answers in a page
 * @return array: array of two variables
 */

function paginate($page=1, $limit=16) {
    $limit = $limit ? : 16;
    $skip = ($page ? $page - 1 : 0) * $limit;
    return [$limit, $skip];
}


/** Error message
 * @param $msg: error message
 * @return array: array of status and error message
 */

function err($msg=null) {
    return ['status' => 0, 'msg'=> $msg];
}


/** Success message
 * @param $data_to_merge: return data
 * @return array: array of status and data
 */

function succ($data_to_merge=[]) {
    $data = ['status' => 1, 'data' => []];
    if ($data_to_merge)
        $data['data'] = array_merge($data['data'], $data_to_merge);
    
    return $data;
}


/** Check whether user has logged in */

function is_logged_in() {
    /* Return user_id if it exists or return false */
    return session('user_id') ?: false;
}


/** Request all
 * @param $key: the variable for checking
 * @param $default: default variable
 * @return boolean: return true if we can find the argument
 */

function rq($key=null, $default=null) {
    if (!$key) return Request::all();
    return Request::get($key, $default);
}


/** homepage */

Route::get('/', function () {
    return view('index');
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


/** Check username exists or not */

Route::any('api/user/exist', function() {
    return user_init()->exist();
});


/** Change password */

Route::any('api/user/change_password', function() {
    return user_init()->change_password();
});


/** Reset password */

Route::any('api/user/reset_password', function() {
    return user_init()->reset_password();
});


/** Validate reset password */

Route::any('api/user/validate_reset_password', function() {
    return user_init()->validate_reset_password();
});


/** Read user info */

Route::any('api/user/read', function() {
    return user_init()->read();
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


/** Remove question */

Route::any('api/question/remove', function() {
    return question_init()->remove();
});


/** Create answer */

Route::any('api/answer/add', function() {
    return answer_init()->add();
});


/** Update answer */

Route::any('api/answer/change', function() {
    return answer_init()->change();
});


/** Vote answer */

Route::any('api/answer/vote', function() {
    return answer_init()->vote();
});


/** Read question */

Route::any('api/answer/read', function() {
    return answer_init()->read();
});


/** Create comment */

Route::any('api/comment/add', function() {
    return comment_init()->add();
});


/** Read comment */

Route::any('api/comment/read', function() {
    return comment_init()->read();
});


/** Remove comment */

Route::any('api/comment/remove', function() {
    return comment_init()->remove();
});


/** Timeline */

Route::any('api/timeline', 'CommonController@timeline');


/** Hold for testing functionality */

Route::any('test', function() {
    dd(user_init()->is_logged_in());
});
