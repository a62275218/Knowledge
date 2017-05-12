<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /*create question*/
    public function add()
    {
        if(!user_init()->is_logged_in())
        {
            return ['status'=>0,'msg'=>'please login'];
        }

        if(!rq('title'))
        {
            return ['status'=>0,'msg'=>'title required'];
        }else {
            $this->title = rq('title');
            $this->user_id = session('id');
        }
        if(rq('desc')){
            $this->desc = rq('desc');
        }
        return $this->save()?
            ['status'=>1,'id'=>$this->id,'msg'=>'post successfully']:
            ['status'=>0,'msg'=>'post failed'];
    }
    /*update question by user*/
    public function change()
    {
        //check login
        if(!user_init()->is_logged_in())
        {
            return ['status'=>0,'msg'=>'please login'];
        }
        //check  parameter
        if(!rq('id'))
        {
            return ['status'=>0,'msg'=>'id required'];
        }else{
            $question = $this->find(rq('id'));
            //check whether question exists
            if(!$question)
            {
                return ['status'=>0,'msg'=>'there is no such question'];
            }
            if($question->user_id !=session('id'))
            {
                return ['status'=> 0,'msg'=>'permission denied'];
            }
            if(rq('title'))
            {
                $question->title = rq('title');
            }
            if(rq('desc'))
            {
                $question->desc = rq('desc');
            }
            //save change
            return $question->save()?
                ['status'=>1,'msg'=> 'insert successfully']:
                ['status'=>0,'msg'=>'insert failed'];
        }
    }
    /*read question*/
    public function read(){
        //return one result based on id
        if(rq('id'))
        {
            return ['status'=>1,'data'=>$this->find(rq('id'))];
        }
        //pagination
        list($limit,$skip) = paginate(rq('page'),rq('limit'));
        //query and collection
        $r = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id','title','desc','user_id','created_at','updated_at'])
            ->keyBy('id');

        return ['status'=>1,'data'=>$r];
    }
    /*delete question*/
    public function remove(){
        //check user
        if(!user_init()->is_logged_in())
        {
            return ['status'=>0,'msg'=>'please login'];
        }
        //check id
        if(!rq('id'))
        {
            return ['status'=>0,'msg'=>'id required'];
        }
        //find question according to id
        $question = $this->find(rq('id'));
        if(!$question)
        {
            return ['status'=>0,'msg'=>'question not exist'];
        }
        if(session('id')!=$question->user_id)
        {
            return ['status'=>0,'msg'=>'permission denied'];
        }
        //delete
        return $question->delete()?
            ['status'=>1,'msg'=>'delete successfully']:
            ['status'=>0,'msg'=>'delete failed'];
    }
}
