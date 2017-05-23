<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    public function timeline(){
        //pass value to two parameters
        list($limit,$skip) = paginate(rq('page'),rq('limit'));
        $questions = question_init()
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();
        $answers = answer_init()
            ->with('users')
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();
        //merge data
        $data = $questions->merge($answers);
        //sort data
        $data = $data->sortByDesc(function($item){
            return $item->created_at;
        });
        $data = $data->values()->all();
        return success(['data' =>$data]);
    }
}
