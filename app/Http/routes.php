<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\User;

//pagination
function paginate($page, $limit){
    $limit = $limit?:16;
    $skip = ($page? $page - 1:0)*$limit;
    return [$limit,$skip];
}

function error($msg=null)
{
    return ['status'=>0,'msg'=>$msg];
}
function success($data_to_add= [])
{
    $data = ['status'=>1, 'data' => []];
    if($data_to_add){
        $data['data'] = $data_to_add;
        return $data;
    }
}
//request parameter
function rq($key=null,$default=null)
{
    if(!$key)
    {
        return Request::all();
    }else{
        return Request::get($key,$default);
    }
}
function user_init()
{
    return $user = new App\User;
}

function question_init()
{
    return $question = new App\Question;
}

function answer_init()
{
    return $answer = new App\Answer;
}

function comment_init()
{
    return $comment = new App\Comment;
}

function is_logged_in(){
    return session('id')?:false;
}

Route::get('/', function () {
    return view('index');
});

Route::get('page/home',function(){
    return view('page.home');
});
Route::get('page/login',function(){
    return view('page.login');
});
Route::get('page/question_add',function(){
    return view('page.question_add');
});
Route::get('page/signup',function(){
    return view('page.signup');
});
Route::get('page/user',function(){
    return view('page.user');
});

Route::group(['prefix'=>'api/'],function(){
    Route::any('user/login',function(){
        return user_init()->login();
    });

    Route::any('user/signup',function()
    {
        return user_init()->signup();
    });

    Route::any('user/logout',function(){
        return user_init()->logout();
    });

    Route::any('user/exist',function(){
        return user_init()->exist();
    });

    Route::any('user/read',function(){
        return user_init()->read();
    });
    Route::any('user/check_login',function(){
        return user_init()->is_logged_in();
    });

    Route::any('user/change_password',function(){
        return user_init()->change_password();
    });

    Route::any('user/reset_password',function(){
        return user_init()->reset_password();
    });

    Route::any('user/validate_reset_password',function(){
        return user_init()->validate_reset_password();
    });

    Route::any('question/add',function(){
        return question_init()->add();
    });

    Route::any('question/change',function(){
        return question_init()->change();
    });

    Route::any('question/read',function(){
        return question_init()->read();
    });

    Route::any('question/remove',function(){
        return question_init()->remove();
    });

    Route::any('answer/add',function(){
        return answer_init()->add();
    });

    Route::any('answer/change',function(){
        return answer_init()->change();
    });

    Route::any('answer/read',function(){
        return answer_init()->read();
    });

    Route::any('answer/vote',function(){
        return answer_init()->vote();
    });

    Route::any('comment/add',function(){
        return comment_init()->add();
    });

    Route::any('comment/read',function(){
        return comment_init()->read();
    });

    Route::any('comment/remove',function(){
        return comment_init()->remove();
    });

    Route::any('timeline','CommonController@timeline');

    //test whether user logged in
    Route::any('test',function(){
        dd(user_init()->is_logged_in());
    });
});




/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
