<div ng-controller="SignupController" class="signup container">
    <div class="card">
        <h1>Sign up</h1>
        <form name="signup_form" ng-submit="User.signup()">
            <div class="input-group">
                <label for="username">Username</label>
                <input name="username" type="text" ng-model="User.signup_data.username"
                       ng-model-options="{debounce:500}" ng-minlength="4"
                       ng-maxlength="24" required>
                <div class="input-error" ng-if="signup_form.username.$touched">
                    <div ng-if="signup_form.username.$error.required">Username required</div>
                    <div ng-if="signup_form.username.$error.minlength || signup_form.username.$error.maxlength">
                        Username should be between 4 and 24 characters
                    </div>
                    <div ng-if="User.signup_data.exist">Username exist</div>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input name="password" type="password" ng-model="User.signup_data.password" ng-minlength="6"
                           ng-maxlength="255" required>
                </div>
                <div class="input-error" ng-if="signup_form.password.$touched">
                    <div ng-if="signup_form.password.$error.required">Password required</div>
                    <div ng-if="signup_form.password.$error.minlength || signup_form.username.$error.maxlength">
                        Password should be at least 6 characters
                    </div>
                </div>
            </div>
            <button type="submit" class="primary" ng-disabled="signup_form.$invalid">Sign Up</button>
        </form>
    </div>
</div>