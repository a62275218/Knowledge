<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //create comment
    public function add(){
        if(!user_init()->is_logged_in())
        {
            return ['status'=>0,'msg'=>'please login'];
        }
        if(!rq('content'))
        {
            return ['status'=>0,'msg'=>'empty content'];
        }
        if(
        (!rq('question_id') && !rq('answer_id')) ||  //none
          (rq('question_id') && rq('answer_id'))  //all
        )
        {
            return ['status'=>0,'msg'=>'question id or answer id is required'];
        }
        //comment question
        if(rq('question_id'))
        {
            $question = question_init()->find(rq('question_id'));
            if(!$question)
            {
                return ['status'=>0,'msg'=>'question not exist'];
            }
            $this->question_id = rq('question_id');
        }else{
            //comment answer
            $answer = answer_init()->find(rq('answer_id'));
            if(!$answer)
            {
                return ['status'=>0,'msg'=>'answer not exist'];
            }
            $this->answer_id = rq('answer_id');
        }
        //reply to comment
        if(rq('reply_to'))
        {
            $target = $this->find(rq('reply_to'));
            if(!$target)
            {
                return ['status'=>0,'msg'=>'comment not exist'];
            }
            if($target->user_id == session('id'))
            {
                return ['status'=>0,'msg'=>'invalid reply'];
            }
            $this->reply_to = rq('reply_to');
        }
        $this->content = rq('content');
        $this->user_id = session('id');
        return $this->save()?
            ['status'=>1,'id'=>$this->id]:
            ['status'=>0,'msg'=>'db insert failed'];
    }
    //read comment
    public function read(){
        if(!rq('question_id') && !rq('answer_id'))
        {
            return ['status'=>0,'msg'=>'question id or answer id required'];
        }
        if(rq('question_id'))
        {
            $question = question_init()->find(rq('question_id'));
            if(!$question)
            {
                return ['status'=>0,'msg'=>'question not exist'];
            }
            $comment = $this->where('question_id',rq('question_id'))->get();
        }else{
            $answer = question_init()->find(rq('answer_id'));
            if(!$answer)
            {
                return ['status'=>0,'msg'=>'answer not exist'];
            }
            $comment = $this->where('answer_id',rq('answer_id'))->get();
        }
        return ['status'=>1,'data'=>$comment->keyBy('id')];
    }

    public function remove(){
        if(!user_init()->is_logged_in())
        {
            return ['status'=>0,'msg'=>'please login'];
        }
        if(!rq('id'))
        {
            return ['status'=>0,'msg'=>'comment id required'];
        }
        $comment = $this->find(rq('id'));
        if(!$comment)
        {
            return ['status'=>0,'msg'=>'comment not exist'];
        }
        if($comment->user_id !=session('id'))
        {
            return ['status'=>0,'msg'=>'permission denied'];
        }
        //delete all the replies to the comment
        $this->where('reply_to',rq('id'))->delete();
        //delete comment
        return $comment->delete()?
            ['status'=>1]:
            ['status'=>0,'msg'=>'db delete failed'];
    }
}
