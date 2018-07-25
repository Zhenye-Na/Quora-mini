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
            <form ng-submit="Question.go_add_question()" id="quick_ask" ng-controller="QuestionAddController">
                <div class="navbar-item">
                    <input ng-model="Question.new_question.title" type="text" placeholder="Search Quora-mini">
                </div>
                <div class="navbar-item">
                    <button type="submit">Add question</button>
                </div>
            </form>
        </div>
        <div class="fr">
            <a ui-sref="home" class="navbar-item">Home</a>
            @if(is_logged_in())
                <a ui-sref="login" class="navbar-item">{{session('username')}}</a>
                <a href="{{url('/api/logout')}}" class="navbar-item">Log out</a>
            @else
                <a ui-sref="login" class="navbar-item">Log in</a>
                <a ui-sref="signup" class="navbar-item">Sign up</a>
            @endif
        </div>
    </div>
</div>


<div class="page">
    <div ui-view></div>
</div>
</body>


<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        Here I am!
    </div>
</script>


<!--Sign up page-->

<script type="text/ng-template" id="signup.tpl">
    <div ng-controller="SignupController" class="signup container">
        <div class="card">
            <h1>Sign up</h1>
<!--            [: User.signup_data :]-->
            <form name="signup_form" ng-submit="User.signup()">
                <div class="input-group">
                    <label>Username: </label>
                    <input name="username"
                           type="text"
                           ng-minlength="4"
                           ng-maxlength="24"
                           ng-model="User.signup_data.username"
                           ng-model-options="{debounce: 400}"
                           required>
                    <div ng-if="signup_form.username.$touched" class="input-error-set">
                        <div ng-if="signup_form.username.$error.required">Username is required</div>
                        <div ng-if="signup_form.username.$error.maxlength || signup_form.username.$error.minlength">Username should be in 4 - 24 cahracters</div>
                        <div ng-if="User.signup_username_exists">Username already exists</div>
                    </div>
                </div>
                <div class="input-group">
                    <label>Password: </label>
                    <input name="password"
                           type="password"
                           ng-minlength="6"
                           ng-maxlength="255"
                           ng-model="User.signup_data.password"
                           required>
                    <div ng-if="signup_form.password.$touched" class="input-error-set">
                        <div ng-if="signup_form.password.$error.required">Password is required</div>
                        <div ng-if="signup_form.password.$error.maxlength || signup_form.password.$error.minlength">Password should be in 6 - 255 cahracters</div>
                    </div>
                </div>
                <button type="submit" class="primary" ng-disabled="signup_form.$invalid">Sign up</button>
            </form>
        </div>
    </div>
</script>


<!--Log in page-->

<script type="text/ng-template" id="login.tpl">
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
</script>


<script type="text/ng-template" id="question.add.tpl">
    <div ng-controller="QuestionAddController" class="question-add container">
        <div class="card">
            <form name="question_add_form" ng-submit="Question.add()">
                <div class="input-group">
                    <label>Title</label>
                    <input type="text"
                           name="title"
                           ng-minlength="5"
                           ng-maxlength="255"
                           ng-model="Question.new_question.title"
                           required>
                </div>
                <div class="input-group">
                    <label>Description</label>
                    <textarea type="text"
                              name="desc"
                              ng-model="Question.new_question.desc">
                    </textarea>
                </div>
                <div class="input-group">
                    <button ng-disabled="question_add_form.title.$invalid"
                            class="primary"
                            type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</script>




</html>