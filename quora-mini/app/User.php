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

        $username = rq('username');
        $password = rq('password');

        if ($username && $password)
            return [$username, $password];

        return false;
    }


    /** Register API */
    
    public function signup()
    {

        /* 1. Examine whether username is null */
        /* 2. Examine whether password is null */

        $examination = $this->isValid();

        if (!$examination)
            return err('Username or passsword is empty!');

        $username = $examination[0];
        $password = $examination[1];


        /* 3. Examine whether username is valid */
        $user_exists = DB::table('users')
            ->where('username', $username)
            ->exists();

        if ($user_exists)
            return err('Username already exists');


        /* 4. Encrypt password and save into database */
        $hashed_password = Hash::make($password);
        $user = $this;
        $user->password = $hashed_password;
        $user->username = $username;
        if ($user->save())
            return succ(['id' => $user->id]);
        else
            return err('Database insert failed!');

    }


    /** Login API */

    public function login()
    {

        /* 1. Examine whether username and password */
        $examination = $this->isValid();

        if (!$examination)
            return err('Username or passsword is empty!');

        $username = $examination[0];
        $password = $examination[1];

        $user = DB::table('users')->where('username', $username)->first();


        /* 2. Examine whether user exists */
        if (!$user)
            return err('User not exists!');


        /* 3. Examine whether password is correct */
        $hashed_password = $user->password;

        if (!Hash::check($password, $hashed_password))
            return err('password or username is not correct!');


        /* 4. Save user info to session */
        session()->put('username', $user->username);
        session()->put('user_id', $user->id);
        
        return succ(['id' => $user->id]);
    }


    /** Check whether user has logged in */
    
    public function is_logged_in()
    {
        /* Return user_id if it exists or return false */
        return session('user_id') ?: false;
    }
    
    
    /** Change password */
    
    public function change_password()
    {
        if (!$this->is_logged_in())
            return err('Please log in first!');
        
        
        if (!rq('old_password') || !rq('new_password'))
            return err('Please type old/new password!');
        
        
        $user = $this->find(session('user_id'));
        
        if (!Hash::check(rq('old_password'), $user->password))
            return err('Invalid password!');
        
        $user->password = bcrypt(rq('new_password'));
        return $user->save() ?
            ['status' => 1] :
            err('db update failed!');
        
    }
    

    /** Log out API */
    
    public function logout()
    {
        /* Delete username and user_id from session */
        session()->forget('username');
        session()->forget('user_id');
        
        return ['status' => 1];
        // return redirect('/');
        
    }


    public function answers()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }
    
}
