<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //add answer API
    public function add()
    {
        //check login
        if (!user_init()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'please login'];
        }
        //check question and content
        if (!rq('question_id') || !rq('content')) {
            return ['status' => 0, 'msg' => 'question id and content are required'];
        }
        $question = question_init()->find(rq('question_id'));
        if (!$question) {
            return ['status' => 0, 'msg' => 'no such question'];
        }
        $answered = $this
            //逗号表示与逻辑,where的数组式查询
            ->where(['question_id' => rq('question_id'), 'user_id' => session('id')])
            ->count();
        if ($answered > 0) {
            return ['status' => 0, 'msg' => 'dulplicated answer'];
        }
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('id');
        return $this->save() ?
            ['status' => 1, 'msg' => 'answer successfully'] :
            ['status' => 0, 'msg' => 'db insert failed'];
    }

    //update answer
    public function change()
    {
        if (!user_init()->is_logged_in()) {
            return ['status' => 0, 'msg' => 'please login'];
        }
        if (!rq('id') || !rq('content')) {
            return ['status' => 0, 'msg' => 'id and content are required'];
        }
        $answer = $this->find(rq('id'));
        if ($answer->user_id != session('id')) {
            return ['status' => 0, 'msg' => 'permission denied'];
        }
        $answer->content = rq('content');
        return $answer->save() ?
            ['status' => 1, 'msg' => 'update successfully'] :
            ['status' => 0, 'msg' => 'db insert failed'];
    }

    //read answer
    public function read()
    {
        if (!rq('id') && !rq('question_id')) {
            return ['status' => 0, 'msg' => 'id or question id is required'];
        }
        if (rq('id')) {
            $answer = $this->find(rq('id'));
            if (!$answer) {
                return ['status' => 0, 'msg' => 'can\'t find answer'];
            } else {
                return ['status' => 1, 'data' => $answer];
            }
        }
        if (!question_init()->find(rq('question_id'))) {
            return ['status' => 0, 'msg' => 'question not exist'];
        }
        $answers = $this
            //逗号表示==，where的单一查询
            ->where('question_id', rq('question_id'))
            ->get()
            ->keyBy('id');
        return ['status' => 1, 'data' => $answers];
    }

    public function vote(){
        if(!user_init()->is_logged_in())
        {
            return ['status'=>0,'msg'=>'please login'];
        }
        if(!rq('id') || !rq('vote'))
        {
            return ['status'=>0,'msg'=>'id and vote required'];
        }
        $answer = $this->find(rq('id'));
        if(!$answer)
        {
            return ['status'=>0,'msg'=>'answer not exist'];
        }
        //1:support 2:against
        $vote = rq('vote') <=1 ?1:2;
        //check whether the user has voted for this answer
        $answer
            ->users()
            ->newPivotStatement()
            ->where('user_id',session('user_id'))
            ->where('answer_id',rq('id'))
            ->delete();

        $answer
            ->users()
            ->attach(session('user_id'),['vote'=>(int)rq('vote')]);
        return ['status'=>1,'vote'=>$vote];
    }

    public function users(){
        return $this
            ->belongsToMany('App\User')
            ->withPivot('vote')
            ->withTimestamps();
    }
}
