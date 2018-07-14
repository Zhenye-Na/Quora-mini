<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;
use DB;

class User extends Model
{
    
    /* Examine username and password is valid */
    
    public function isValid() {

        $username = Request::get('username');
        $password = Request::get('password');

        if ($username && $password)
            return [$username, $password];

        return false;
    }


    /* Register API */
    
    public function signup() {

        /* 1. Examine whether username is null */
        /* 2. Examine whether password is null */

        $examination = $this->isValid();

        if (!$examination)
            return ['status' => 0, 'msg' => 'Username or passsword is empty!'];

        $username = $examination[0];
        $password = $examination[1];


        /* 3. Examine whether username is valid */

        $user_exists = DB::table('users')
            ->where('username', $username)
            ->exists();

        if ($user_exists)
            return ['status' => 0, 'msg' => 'Username already exists'];


        /* 4. Encrypt password and save into database */

        $hashed_password = Hash::make($password);  // Also bcrypt($password)
        $user = $this;
        $user->password = $hashed_password;
        $user->username = $username;
        if ($user->save())
            return ['status' => 1, 'id' => $user->id];
        else
            return ['status' => 0, 'msg' => 'Database insert failed!'];

    }



    /* Login API */

    public function login() {

        /* 1. Examine whether username and password */

        $examination = $this->isValid();

        if (!$examination)
            return ['status' => 0, 'msg' => 'Username or passsword is empty!'];

        $username = $examination[0];
        $password = $examination[1];

        $user = DB::table('users')
            ->where('username', $username)
            ->first();


        /* 2. Examine whether user exists */

        if (!$user)
            return ['status' => 0, 'msg' => 'User not exists!'];


        /* 3. Examine whether password is correct */

        $hashed_password = $user->password;

        if (!Hash::check($password, $hashed_password))
            return ['status' => 0, 'msg' => 'password or username is not correct!'];

        /* 4. Save user info to session */

        session()->put('username', $user->username);
        session()->put('user_id', $user->id);

        dd(session()->all());


        return ['status' => 1, 'id' => $user->id];
    }
    
}
