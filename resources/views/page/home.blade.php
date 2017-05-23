<div ng-controller="HomeController" class="home card container">
    <h1>Recent Posts</h1>
    <div class="hr"></div>
    <div class="item-set">
        <div class="feed item clearfix" ng-repeat="question in Timeline.data">
            <div ng-if="question.question_id" class="vote">
                <div class="up" ng-click="Timeline.vote({id:question.id, vote:1})">[[question.upvote_count]]<br>like</div>
                <div class="down" ng-click="Timeline.vote({id:question.id, vote:2})">[[question.downvote_count]]<br>dislike</div>
            </div>
            <div class="feed-item-content">
                <div ng-if="question.question_id" class="content-act">[[question.user.username]] added answer</div>
                <div ng-if="!question.question_id" class="content-act">[[question.user.username]] added question</div>
                <div class="content-title">[[question.title]]</div>
                <div class="content-owner">[[question.user.username]]
                    <span class="desc">my name is</span>
                </div>
                <div class="content-main">[[question.desc]]</div>
                <div class="action-set">
                    <div class="comment">Comment</div>
                </div>
                <div class="comment-block">
                    <div class="hr"></div>
                    <div class="comment-item-set">
                        <div class="rect"></div>
                        <div class="comment-item clearfix">
                            <div class="user">user</div>
                            <div class="comment-content">comment content</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr"></div>
        </div>
        <div class="center" ng-if="Timeline.pending">Loading...</div>
        <div class="center" ng-if="Timeline.no_more_data">No more data</div>
    </div>
</div>