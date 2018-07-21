<!doctype html>
<html ng-app="quora-mini">
<head>
    <meta charset="UTF-8">
    <title>Quora-mini</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
</head>

<body>
<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">Quora<sup>mini</sup></div>
            <div class="navbar-item">
                <input type="text">
            </div>
        </div>
        <div class="fr">
            <a ui-sref="home" class="navbar-item">Home</a>
            <a ui-sref="login" class="navbar-item">Log in</a>
            <a ui-sref="signup" class="navbar-item">Sign up</a>
        </div>
    </div>
</div>

<div class="page">
    <div ui-view></div>
</div>
</body>


<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus assumenda culpa deleniti doloribus earum in labore laudantium libero nam necessitatibus perspiciatis, porro quisquam ratione repellendus rerum sapiente tempora, tenetur voluptas!
    </div>
</script>


<script type="text/ng-template" id="signup.tpl">
    <div ng-controller="SignupController" class="signup container">
        <div class="card">
            <h1>Sign up</h1>
            [: User.signup_data :]
            <form name="signup_form" ng-submit="User.signup()">
                <div>
                    <label>Username: </label>
                    <input name="username"
                           type="text"
                           ng-minlength="4"
                           ng-maxlength="24"
                           ng-model="User.signup_data.username"
                           required>
                    <div class="input-error-set">
                        <div ng-if="signup_form.username.$error.required">Username is required</div>
                    </div>
                </div>
                <div>
                    <label>Password: </label>
                    <input name="password"
                           type="password"
                           ng-minlength="6"
                           ng-maxlength="255"
                           ng-model="User.signup_data.password"
                           required>
                </div>
                <button type="submit" ng-disabled="signup_form.$invalid">Sign up</button>
            </form>
        </div>
    </div>
</script>


<script type="text/ng-template" id="login.tpl">
    <div class="login container">

    </div>
</script>

</html>