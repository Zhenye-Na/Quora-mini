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
                    <input ng-model="Question.new_question.title" type="text">
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
    <div ng-controller="HomeController" class="home card container">
        <h1>Newly Updated</h1>
        <div class="hr"></div>
        <div class="item-set">
            <div ng-repeat="item in Timeline.data" class="item">
                <div class="vote"></div>
                <div class="feed-item-content">

                    <div ng-if="item.question_id" class="content-act">[: item.user.username :] added answer</div>
                    <div ng-if="!item.question_id" class="content-act">[: item.user.username :] added question</div>
                    <div class="title">[: item.title :]</div>

                    <div class="content-owner">
                        [: item.user.username :]
                        <span class="desc">[: item.user.desc :]</span>
                    </div>

                    <div class="content-main">
                        [: ite.desc :]
                    </div>

                    
                    <div class="action-set">
                        <div class="comment">comment</div>
                    </div>



                    <div class="comment-block">
                        <div class="hr"></div>
                        <div class="comment-item-set">
                            <div class="rect"></div>

                            <div class="comment-item clearfix">
                                <div class="user">UsernameIsHere</div>
                                <div class="comment-content">
                                    Most of the time when someone argues Superman losing a fight, it’s because of an inherent
                                    limitation of his character.kryptonite-bearing fist against his jaw. Thor, too, has
                                    inherent limitations.ryptonite-bearing fist agaryptonite-bearing fist agaryptonite-bearing fist aga
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    
                    
                </div>
                <div class="hr"></div>
            </div>

        </div>

<!--        <div ng-if="Timeline.pending" class="tac">-->
<!--            <img src="https://c.s-microsoft.com/en-us/CMSImages/minifindstore_spin.gif?version=45117834-d17e-3a16-5765-62399907b530" width="5%">-->
<!--        </div>-->
        <div ng-if="Timeline.no_more_data" class="tac">
            刷呀刷... 刷不出来了 _(:з」∠)_
        </div>

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


<!--Add question-->

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
                    <button ng-disabled="question_add_form.$invalid"
                            class="primary"
                            type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</script>




</html>