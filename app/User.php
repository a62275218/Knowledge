<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;
use Blade;

class User extends Model
{
    /*signup API*/
    public function signup()
    {
        $user = $this;
        $username_and_password = $user->verify_username_and_password();
        if (!$username_and_password) {
            return error('username and password are required');
        }else{
            $username = $username_and_password[0];
            $password = $username_and_password[1];
        }
        $user_exists = $user
            ->where('username', $username)
            ->exists();
        if ($user_exists) {
            return error('user already exist');
        } else {
            $hashed_password = Hash::make($password);

            $user->password = $hashed_password;
            $user->username = $username;
            $user->save();
            if ($user->save()) {
                return success(['id' => $user->id]);
            } else {
                return 1;
            }
        }
    }

    //get uer information
    public function read(){
        if(!rq('id'))
        {
            return error('id required');
        }
        $user = $this->find(rq('id'));
        //information title of user
        $get = ['id','username','avatar_url','intro'];
        $user = $this->find(rq('id'),$get);
        $data = $user->toArray();
        $answer_count = answer_init()->where('user_id',rq('id'))->count();
        $question_count = question_init()->where('user_id',rq('id'))->count();
        $data ['$answer_count'] = $answer_count;
        $data ['$question_count'] = $question_count;
        return success($data);
    }

    /*login API*/
    public function login()
    {
        /*检查用户名密码是否存在*/
        $username_and_password = $this->verify_username_and_password();
        if(!$username_and_password){
            return error('username and password are required');
        }else{
            $username = $username_and_password[0];
            $password = $username_and_password[1];
        }
        /*检查用户是否存在*/
        $user = $this->where('username',$username)->first();
        if(!$user){
            return error('user not exist');
        }else{
            /*检查密码是否正确*/
            $hashed_password = $user->password;
            if(!Hash::check($password,$hashed_password)){
                return error('wrong password');
            }else{
                session()->set('username',$user->username);
                session()->set('id',$user->id);
                return success(['msg'=>'welcome','user'=>$user]);
            }
        }
    }

    /*logout API*/
    public function logout()
    {
        session()->forget('username');
        session()->forget('id');
        //return redirect('/');
        return success(['msg'=>'success']);
    }

    public function exist(){
        return success(['count'=>$this->where('username',rq('username'))->count()]);
    }

    public function verify_username_and_password()
    {
        $username = rq('username');
        $password = rq('password');
        if ($username && $password) {
            return [$username,$password];
        }else{
            return false;
        }
    }

    public function is_logged_in()
    {
        return is_logged_in();
    }

    public function change_password()
    {
        if (!$this->is_logged_in()) {
            return error('please login');
        }
        if (!rq('old_password') || !rq('new_password')) {
            return error('both old and new passwords are required');
        }
        $user = $this->find(session('id'));
        if (!Hash::check(rq('old_password'), $user->password)) {
            return error('password doesn\'t match');
        }
        $user->password = bcrypt(rq('new_password'));
        return $user->save() ?
            success(['msg' => 'password changed']):
            error('db insert failed');
    }
    //forget password
    public function reset_password(){
        if($this->is_robot(10))
        {
            return error('max frequency reached');
        }
        if(!rq('phone'))
        {
            return error('phone is required');
        }
        $user = $this->where('phone',rq('phone'))->first();
        if(!$user)
        {
            return error('invalid phone number');
        }

        $captcha =$this->generate_captcha();
        $this ->send_sms();
        //save last time of validation
        session()->set('last_action_time',time());
        $user->phone_captcha = $captcha;
        return $user->save()?
            success(['msg'=>'captcha inserted']):
            error('db insert failed');
    }

    public function is_robot($time = 20)
    {
        //user hasn't use this api
        if(!session('last_action_time'))
        {
            return false;
        }
        $current_time = time();
        $last_active_time = session('last_action_time');
        $elapsed = $current_time - $last_active_time;
        return !($elapsed > $time);
    }

    public function validate_reset_password(){
        if(!rq('phone') || !rq('phone_captcha') || !rq('new_password')){
            return error('phone, new password and phone captcha required');
        }
        //check whether user exist
        $user = $this->where(['phone'=>rq('phone'),'phone_captcha'=>rq('phone_captcha')])
            ->first();
        if(!$user)
        {
            return error('invalid phone or captcha');
        }
        $user->password = bcrypt(rq('new_password'));
        return $user->save()?
            success(['msg'=>'new password saved']):
            error('db insert failed');
    }

    public function send_sms()
    {
        return true;
    }

    //generate captcha
    public function generate_captcha()
    {
        return rand(1000,9999);
    }

    public function answers(){
        return $this
            ->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }
}
