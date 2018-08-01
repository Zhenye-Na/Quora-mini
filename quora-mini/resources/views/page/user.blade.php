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

        <h2>Questions</h2>
        <div ng-repeat="(key, value) in User.his_questions">
            [: value.title :]
        </div>
        
        <h2>Answers</h2>
        <div class="feed item" ng-repeat="(key, value) in User.his_answers">

<!--            <div ng-if="item.question_id" class="vote ng-scope">-->
<!--                <div ng-click="Timeline.vote({id: item.id, vote: 1})" class="up">👍 [: item.upvote_count :]</div>-->
<!--                <div ng-click="Timeline.vote({id: item.id, vote: 2})" class="down">👎 [: item.downvote_count :]</div>-->
<!--            </div>-->



            <div ui-sref="" class="title">
                [: value.question.title :]
            </div>
            [: value.content :]

            <div class="action-set">
                <div class="comment">Updated on [: value.updated_at :]</div>
            </div>

        </div>
        
    </div>

</div>