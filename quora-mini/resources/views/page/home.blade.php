<!--Homepage-->

<div ng-controller="HomeController" class="home card container">
    <h1>Newly Updated</h1>
    <div class="hr"></div>
    <div class="item-set">
        <div ng-repeat="item in Timeline.data" class="feed item clearfix">

            <div ng-if="item.question_id" class="vote ng-scope">
                <div ng-click="Timeline.vote({id: item.id, vote: 1})" class="up">👍 [: item.upvote_count :]</div>
                <div ng-click="Timeline.vote({id: item.id, vote: 2})" class="down">👎 [: item.downvote_count :]</div>
            </div>

            <div class="feed-item-content">
                <div ng-if="item.question_id" class="content-act">
                    <a ui-sref="user({id: item.user.id})">[: item.user.username :]</a>
                    added answer
                </div>
                <div ng-if="!item.question_id" class="content-act">
                    <a ui-sref="user({id: item.user.id})">[: item.user.username :]</a>
                    added question
                </div>
                <div ng-if="item.question_id" class="title"
                     ui-sref="question.detail({id: item.question.id})">[: item.question.title :]
                </div>
                <div ui-sref="question.detail({id: item.id})"
                     class="title">[: item.title :]
                </div>
                <div class="content-owner">
                    [: item.user.username :]
                    <span class="desc">[: item.user.desc :]</span>
                </div>
                <div ng-if="item.question_id" class="content-main">
                    <a ui-sref="question.detail({id: item.question_id, answer_id: item.id})">[: item.content :]</a>
                    <div class="gray">
                        [: item.updated_at :]
                    </div>
                </div>

                
                <div class="action-set">
                    <span class="anchor gray" ng-click="item.show_comment = !item.show_comment">
                        <span ng-if="item.show_comment">Cancel</span>
                        Comment
                    </span>

                    <span class="gray">
                        <a ng-click="Answer.answer_form = item"
                           ng-if="item.user_id == his.id"
                           class="anchor">
                            Edit
                        </a>
                        <a ng-click="Answer.delete(item.id)"
                           ng-if="item.user_id == his.id"
                           class="anchor">
                            Delete
                        </a>
                        <a>
                            [: item.updated_at :]
                        </a>
                    </span>
                </div>


                <div ng-if="item.show_comment" comment-block answer-id="item.id">
                    [: item.content :]
                </div>


            </div>
            <div class="hr"></div>
        </div>

    </div>

    <div ng-if="Timeline.no_more_data" class="tac">
        刷呀刷... 刷不出来了 _(:з」∠)_
    </div>

</div>