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

                <div ng-if="item.question_id" class="content-act">[: item.user.username :] added answer</div>
                <div ng-if="!item.question_id" class="content-act">[: item.user.username :] added question</div>
                <div class="title">[: item.title :]</div>

                <div class="content-owner">
                    [: item.user.username :]
                    <span class="desc">[: item.user.desc :]</span>
                </div>

                <div class="content-main">
                    [: item.content :]
                </div>


                <div class="action-set">
                    <div class="comment">comment</div>
                </div>



                <div class="comment-block">
                    <div class="hr"></div>
                    <div class="comment-item-set">
                        <div class="rect"></div>

                        <div class="comment-item clearfix">
                            <div class="user">UsernameIsHere</div>
                            <div class="comment-content">
                                Most of the time when someone argues Superman losing a fight, it’s because of an inherent
                                limitation of his character.kryptonite-bearing fist against his jaw. Thor, too, has
                                inherent limitations.ryptonite-bearing fist agaryptonite-bearing fist agaryptonite-bearing fist aga
                            </div>
                        </div>

                    </div>
                </div>



            </div>
            <div class="hr"></div>
        </div>

    </div>

    <!--        <div ng-if="Timeline.pending" class="tac">-->
    <!--            <img src="https://c.s-microsoft.com/en-us/CMSImages/minifindstore_spin.gif?version=45117834-d17e-3a16-5765-62399907b530" width="5%">-->
    <!--        </div>-->
    <div ng-if="Timeline.no_more_data" class="tac">
        刷呀刷... 刷不出来了 _(:з」∠)_
    </div>

</div>