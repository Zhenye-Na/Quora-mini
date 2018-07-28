<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class User extends Model
{
    
    /** Get user info API */

    public function read()
    {
        if (!rq('id'))
            return err('User id is required!');

        $id = rq('id') === 'self' ?
            session('user_id') :
            rq('id');

        $get = ['id', 'username', 'avatar_url', 'intro'];
        
        $user = $this->find($id, $get);
        $data = $user->toArray();
        
        $answer_count = answer_init()->where('user_id', $id)->count();
        $question_count = question_init()->where('user_id', $id)->count();

        $data['question_count'] = $question_count;
        $data['answer_count'] = $answer_count;
        
        return succ($data);
    }
    
    
    /** Examine username and password is valid */
    
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
        $user_exists = $this
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

        $user = $this->where('username', $username)->first();


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
        return is_logged_in();
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
            succ() :
            err('db update failed!');
        
    }


    /** Reset password API */

    public function reset_password()
    {

        if ($this->is_robot())
            return err('Please wait for at 10 secs to resend again!');

        
        if(!rq('phone'))
            return err('Phone number is required!');
        
        $user = $this->where('phone', rq('phone'))->first();
        
        if (!$user)
            return err('Invalid phone number');
        
        /* 生成验证码 */
        $captcha = $this->generate_captcha();
        $user->phone_captcha = $captcha;
        
        /* 保存验证码 */
        if ($user->save())
        {
            succ();
            $this->send_sms();  // 发送验证码至手机
            $this->update_robot_time();
        }
        return err('da updated failed!');
        
    }


    /** Validate reset password API */

    public function validate_reset_password()
    {

        if ($this->is_robot(2))
            return err('Maximum frequency reached!');

        if (!rq('phone') || !rq('phone_captcha') || !rq('new_password'))
            return err('phone, new password and captcha are required!');

        /* Check whether user exists */
        $user = $this->where([
            'phone'         => rq('phone'),
            'phone_captcha' => rq('phone_captcha')
        ])->first();

        if (!$user)
            return err('Invalid phone or phone captcha!');

        /* Encrypt new password */
        $user->password = bcrypt(rq('new_password'));
        $this->update_robot_time();

        return $user->save()?
            succ():
            err('db updated failed!');
    }


    /** Send sms demo */
    public function send_sms()
    {
        return true;
    }
    
    
    /** Generate random number */
    public function generate_captcha()
    {
        return rand(1000, 9999);
    }


    /** 检查当前用户是否是机器人
     * @param $time: waiting time
     * @return boolean: bot or not
     */
    public function is_robot($time=10)
    {
        if (!session('last_action_time'))
            return false;

        $current_time = time();
        $last_action_time = session('last_action_time');

        $elapsed = $current_time - $last_action_time;
        return !($elapsed > $time);
    }
    
    /** 更新刷新时间 */
    public function update_robot_time()
    {
        session()->set('last_action_time', time());
    }
    

    /** Log out API */
    
    public function logout()
    {
        /* Delete username and user_id from session */
        session()->forget('username');
        session()->forget('user_id');
        
        return succ();
        // return redirect('/');
        
    }


    public function answers()
    {
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }


    public function questions()
    {
        return $this
            ->belongsToMany('App\Question')
            ->withPivot('vote')
            ->withTimestamps();
    }


    public function exist() {
        return succ(['count' => $this->where(rq())->count()]);
    }

}
