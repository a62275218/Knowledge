<!doctype html>
<html lang="en" ng-app="knowledge" user-id="{{session('id')}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Knowledge</title>
    <link rel="stylesheet" href="{{URL::asset('node_modules/normalize-css/normalize.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/base.css')}}">
    <script src="{{URL::asset('node_modules/jquery/dist/jquery.js')}}"></script>
    <script src="{{URL::asset('node_modules/angular/angular.js')}}"></script>
    <script src="{{URL::asset('node_modules/angular-ui-router/release/angular-ui-router.js')}}"></script>
    <script src="{{URL::asset('js/base.js')}}"></script>
    <script src="{{URL::asset('js/common.js')}}"></script>
    <script src="{{URL::asset('js/question.js')}}"></script>
    <script src="{{URL::asset('js/user.js')}}"></script>
    <script src="{{URL::asset('js/answer.js')}}"></script>
</head>
<body>
<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">Knowledge</div>
            <form id="quick-ask" ng-controller="QuestionAddController" ng-submit="Question.go_add_question()">
                <div class="navbar-item">
                    <input type="text" ng-model="Question.new_question.title" placeholder="Type the question title">
                </div>
                <div class="navbar-item">
                    <button type="submit">Ask</button>
                </div>
            </form>
        </div>
        <div class="fr">
            <a ui-sref="home" class="navbar-item">Home</a>
            @if(is_logged_in())
                <a ui-sref="login" class="navbar-item">{{session('username')}}</a>
                <a href="{{url('api/user/logout')}}">Logout</a>
            @else
                <a ui-sref="login" class="navbar-item">Login</a>
                <a ui-sref="signup" class="navbar-item">Signup</a>
            @endif
        </div>
    </div>
</div>
<div class="page">
    <div ui-view></div>
</div>
</body>
</html>