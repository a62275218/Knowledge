<div ng-controller="QuestionAddController" class="question-add container">
    <div class="card">
        <form name="question_form" ng-submit="Question.add()">
            <div class="input-group">
                <label for="title">Question Title</label>
                <input name="title" type="text" ng-model="Question.new_question.title" ng-minlength="5" ng-maxlength="255" required>
            </div>
            <div class="input-group">
                <label for="desc">Question Description</label>
                <textarea name="desc" ng-model="Question.new_question.desc" required/>
            </div>
            <button type="submit" class="primary" ng-disabled="question_form.title.$error">Ask</button>
        </form>
    </div>
</div>