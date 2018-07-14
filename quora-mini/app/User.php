<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class User extends Model
{
    
    /* Register API */
    
    public function signup() {

        $username = Request::get('username');
        $password = Request::get('password');

        /* 1. Examine whether username is null */

        if (!$username)
            return ['status' => 0, 'msg' => 'Username cannot be empty!'];

        /* 1. Examine whether password is null */

        if (!$password)
            return ['status' => 0, 'msg' => 'Password cannot be empty!'];

        /* 2. Examine whether username is valid */

        $user_exists = $this
            ->where('username', $username)
            ->exists();

        if ($user_exists)
            return ['status' => 0, 'msg' => 'Username already exists'];

        /* 3. Encrypt password and save into database */

        $hashed_password = Hash::make($password);  // Also bcrypt($password)
        $this->password = $hashed_password;
        $this->username = $username;
        if ($this->save())
            return ['status' => 1, 'id' => $user->id];
        else
            return ['status' => 0, 'msg' => 'Database insert failed!'];

    }


    
}
