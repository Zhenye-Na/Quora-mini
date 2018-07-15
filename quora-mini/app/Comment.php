<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /** Create comment API */

    public function add()
    {

        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return ['status' => 0, 'msg' => 'Please log in first!'];

        /* Check comment content */
        if (!rq('content'))
            return ['status' => 0, 'msg' => 'Comment content is required!'];

        /* Either question_id or answer_id, cannot exist at the same time */
        if ( (!rq('question_id') && !rq('answer_id')) || (rq('question_id') && rq('answer_id')) )
            return ['status' => 0, 'msg' => 'question_id or answer_id is required!'];

        /* Comment on question or answer */
        if (rq('question_id'))
        {
            $question = question_init()->find(rq('question_id'));
            if (!$question)
                return ['status' => 0, 'msg' => 'Question not exists!'];
            $this->question_id = rq('question_id');
        } else
        {
            $answer = answer_init()->find(rq('answer_id'));
            if (!$answer)
                return ['status' => 0, 'msg' => 'Answer not exists!'];
            $this->answer_id = rq('answer_id');
        }

        /* Comment on other comments */
        if (rq('reply_to'))
        {
            $target = $this->find(rq('reply_to'));
            if (!$target)
                return ['status' => 0, 'msg' => 'Target comment cannot be found!'];
            
            /* Check target comment exists or reply to your own comments */
            if ($target->user_id == session('user_id'))
                return ['status' => 0, 'msg' => 'You cannot reply to your own comment!'];
            $this->reply_to = rq('reply_to');
        }


        $this->content = rq('content');
        $this->user_id = session('user_id');

        return $this->save() ?
            ['status' => 1, 'id' => $this->id] :
            ['status' => 0, 'msg' => 'db insert failed!'];
    }




}
