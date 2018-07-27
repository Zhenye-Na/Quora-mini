<!--Log in-->

<div ng-controller="LoginController" class="login container">
    <div class="card">
        <h1>Log in</h1>
        <form name="login_form" ng-submit="User.login()">
            <div class="input-group">
                <label>Username:</label>
                <input type="text"
                       name="username"
                       ng-model="User.login_data.username"
                       required>
            </div>
            <div class="input-group">
                <label>Password:</label>
                <input type="password"
                       name="password"
                       ng-model="User.login_data.password"
                       required>
            </div>
            <div ng-if="User.login_failed" class="input-error-set">
                Username or password is invalid
            </div>
            <div class="input-group">
                <button type="submit"
                        class="primary"
                        ng-disabled="login_form.username.$error.required || login_form.password.$error.required">Log in</button>
            </div>
        </form>
    </div>
</div>