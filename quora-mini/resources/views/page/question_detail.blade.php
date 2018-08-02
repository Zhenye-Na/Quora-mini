<!--Question details-->

<div ng-controller="QuestionDetailController" class="container question-detail">
    <div class="card">
        <h1>[: Question.current_question.title :]</h1>
        <div class="desc">[: Question.current_question.desc :]</div>

        <small ng-if="his.id == Question.current_question.user_id"
               ng-click="Question.show_update_form = !Question.show_update_form"
               class="anchor gray">
            <span ng-if="Question.show_update_form">Cancel</span>
            Edit question
        </small>



        <form ng-if="Question.show_update_form"
              name="question_update_form"
              class="gray_card well"
              ng-submit="Question.update_question()">
            <div class="input-group">
                <label>Title</label>
                <input type="text"
                       name="title"
                       ng-minlength="5"
                       ng-maxlength="255"
                       ng-model="Question.current_question.title"
                       required>
            </div>
            <div class="input-group">
                <label>Description</label>
                    <textarea type="text"
                              name="desc"
                              ng-model="Question.current_question.desc">
                    </textarea>
            </div>
            <div class="input-group">
                <button ng-disabled="question_add_form.$invalid"
                        class="primary"
                        type="submit">Submit</button>
            </div>
        </form>



        <div><span class="gray">Answers: [: Question.current_question.answers_with_user_info.length :]</span></div>

        <div class="hr"></div>
        
        <div class="feed item clearfix">
            <div ng-if="helper.obj_length(Question.current_question.answers_with_user_info) == 0">
                <div class="gray tac well">还没有回答,快来抢沙发啊...喵 σ`∀´)σ </div>
                <div class="hr"></div>
            </div>
            <div ng-if="!Question.current_answer_id || Question.current_answer_id == item.id" ng-repeat="item in Question.current_question.answers_with_user_info">

                <div class="vote ng-scope">
                    <div ng-click="Question.vote({id: item.id, vote: 1})" class="up">👍 [: item.upvote_count :]</div>
                    <div ng-click="Question.vote({id: item.id, vote: 2})" class="down">👎 [: item.downvote_count :]</div>
                </div>


                <div class="feed-item-content">

                    <div>
                        <span ui-sref="user({id: item.user.id})">[: item.user.username :]</span>
                    </div>

                    <div>[: item.content :]

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

                    </div>
                </div>

                <div ng-if="item.show_comment" comment-block answer-id="item.id">
                    [: item.content :]
                </div>

                <div class="hr"></div>

            </div>

        </div>


        <div>

            <form ng-submit="Answer.add_or_update(Question.current_question.id)" class="answer_form" name="answer_form">
                <div class="input-group">
                    <label>Your Answer:</label>
                    <textarea type="text"
                              name="Content"
                              ng-minlength="5"
                              ng-model="Answer.answer_form.content"
                              placeholder="Add answer here..."
                              required>
                    </textarea>
                </div>
                <div class="input-group">
                    <button ng-disabled="answer_form.$invalid"
                            class="primary"
                            type="submit">Submit</button>
                </div>
            </form>

        </div>

    </div>

</div>