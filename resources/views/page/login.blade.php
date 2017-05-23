<div ng-controller="LoginController" class="login container">
    <div class="card">
        <h1>Login</h1>
        <form name="login_form" ng-submit="User.login()">
            <div class="input-group">
                <label for="username">Username</label>
                <input name="username" type="text" ng-model="User.login_data.username" required/>
                <label for="password">Password</label>
                <input name="password" type="password" ng-model="User.login_data.password" required/>
                <div class="input-error">
                    <div ng-if="User.login_data.login_status == false">Username or password is wrong</div>
                </div>
            </div>
            <button type="submit" class="primary"
                    ng-disabled="login_form.username.$error.required || login_form.password.$error.required">Login
            </button>
        </form>
    </div>
</div>