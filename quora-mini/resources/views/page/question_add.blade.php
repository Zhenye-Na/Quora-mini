<!--Add question-->

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