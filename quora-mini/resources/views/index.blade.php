<!doctype html>
<html ng-controller="BaseController" ng-app="quora-mini" user-id="{{session('user_id')}}">
<head>
    <meta charset="UTF-8">
    <title>Quora-mini</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/answer.js"></script>
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
                    <input ng-model="Question.new_question.title" type="text" placeholder="Search 🤓">
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

<script type="text/ng-template" id="comment.tpl">
    <div class="comment-block">
        <div class="hr"></div>

        <div class="comment-item-set">
            <div class="rect"></div>


            <div ng-if="!helper.obj_length(data.data)" class="gray tac well">暂无评论</div>
            <div ng-if="helper.obj_length(data.data)"
                 ng-repeat="item in data.data"
                 class="comment-item clearfix">
                <div class="user">[: item.user.username :]: </div>
                <div class="comment-content">
                    [: item.content :]
                </div>
            </div>

        </div>

        <div class="input-group">
            <form ng-submit="Answer.comment_create.add_comment()" class="comment-form">
                <input type="text"
                       ng-model="Answer.new_comment.content"
                       placeholder="Please type your comment here">
                <button class="primary"
                        type="submit">Comment</button>
            </form>
            
        </div>

    </div>

</script>

</body>

</html>