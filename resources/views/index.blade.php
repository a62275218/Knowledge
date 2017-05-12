<!doctype html>
<html lang="en" ng-app="knowledge">
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
</head>
<body>
<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">Knowledge</div>
            <div class="navbar-item">
                <input type="text" placeholder="search">
            </div>
        </div>
        <div class="fr">
            <a ui-sref="home" class="navbar-item">Home</a>
            <a ui-sref="login" class="navbar-item">Login</a>
            <a ui-sref="signup" class="navbar-item">Signup</a>
        </div>
    </div>
</div>
<div class="page">
    <div ui-view></div>
</div>
<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        Home
    </div>
</script>
<script type="text/ng-template" id="signup.tpl">
    <div ng-controller="SignupController" class="signup container">
        <div class="card">
            <h1>Sign up</h1>
            <form name="signup_form" ng-submit="User.signUp()">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" ng-model="User.signup_data.username" ng-model-options="{updateOn:'blur'}" ng-minlength="4"
                           ng-maxlength="24" required>
                    <div class="input-error">
                        <div ng-if="signup_form.username.$error.required">Username required</div>
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input name="password" type="text" ng-model="User.signup_data.password" ng-minlength="6"
                               ng-maxlength="255" required>
                    </div>
                    <div class="input-error">
                        <div ng-if="signup_form.password.$error.required">Password required</div>
                    </div>
                </div>
                <button type="submit" ng-disabled="signup_form.$invalid">Sign Up</button>
            </form>
        </div>
    </div>
</script>
<script type="text/ng-template" id="login.tpl">
    <div class="home container">
        Login
    </div>
</script>
</body>
</html>