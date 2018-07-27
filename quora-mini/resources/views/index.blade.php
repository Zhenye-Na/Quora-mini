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
    <script src="/js/common.js"></script>
    <script src="/js/question.js"></script>
    <script src="/js/user.js"></script>
</head>

<body>

<div class="navbar clearfix">
    <div class="container">
        <div class="fl">
            <div ui-sref="home" class="navbar-item brand">Quora<sup>mini</sup></div>
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

</html>