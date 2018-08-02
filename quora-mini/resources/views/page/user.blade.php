<!--User info-->

<div ng-controller="UserController">
    <div class="user card container">
        <h1>User info</h1>

        <div class="hr"></div>

        <div class="basic">
            <div class="info_item clearfix">
                <div>Username</div>
                <div>[: User.current_user.username :]</div>
            </div>
            <div class="info_item clearfix">
                <div>Intro</div>
                <div>[: User.current_user.intro || '该用户很懒, 什么都不想写...' :]</div>
            </div>
        </div>

        <div class="hr"></div>

        <h2>Questions</h2>
        <div ng-repeat="(key, value) in User.his_questions" class="title">
            <a ui-sref="question.detail({id: value.id})">[: value.title :]</a>
        </div>

        <div class="hr"></div>

        <h2>Answers</h2>
        <div class="feed item" ng-repeat="(key, value) in User.his_answers">
            <div ui-sref="question.detail({id: value.question.id})" class="title">
                [: value.question.title :]
            </div>
            [: value.content :]

            <div class="action-set">
                <div class="comment">Updated on [: value.updated_at :]</div>
            </div>

            <div class="hr"></div>
        </div>
        
    </div>

</div>