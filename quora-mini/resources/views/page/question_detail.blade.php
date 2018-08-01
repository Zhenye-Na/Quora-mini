<div ng-controller="QuestionDetailController" class="container question-detail">

    <div class="card">

        <h1>[: Question.current_question.title :]</h1>

        <div class="desc">
            [: Question.current_question.desc :]
        </div>

        <div><span class="gray">Answers: [: Question.current_question.answers_with_user_info.length :]</span></div>

        <div class="hr"></div>
        
        <div class="feed item clearfix">

            <div ng-repeat="item in Question.current_question.answers_with_user_info">

                <div class="vote">
                    <div ng-click="Question.vote({id: item.id, vote: 1})" class="up">👍 [: item.upvote_count :]</div>
                    <div ng-click="Question.vote({id: item.id, vote: 2})" class="down">👎 [: item.downvote_count :]</div>
                </div>


                <div class="feed-item-content">

                    <div>
                        <span ui-sref="user({id: item.user.id})">[: item.user.username :]</span>
                    </div>
                    <div>[: item.content :]</div>
                    <div class="hr"></div>

                </div>

            </div>



        </div>


    </div>

</div>