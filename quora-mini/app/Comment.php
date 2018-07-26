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
            return err('Please log in first!');

        /* Check comment content */
        if (!rq('content'))
            return err('Comment content is required!');

        /* Either question_id or answer_id, cannot exist at the same time */
        if ( (!rq('question_id') && !rq('answer_id')) || (rq('question_id') && rq('answer_id')) )
            return err('question_id or answer_id is required!');

        /* Comment on question or answer */
        if (rq('question_id'))
        {
            $question = question_init()->find(rq('question_id'));
            if (!$question)
                return err('Question not exists!');
            $this->question_id = rq('question_id');
        } else
        {
            $answer = answer_init()->find(rq('answer_id'));
            if (!$answer)
                return err('Answer not exists!');
            $this->answer_id = rq('answer_id');
        }

        /* Comment on other comments */
        if (rq('reply_to'))
        {
            $target = $this->find(rq('reply_to'));
            if (!$target)
                return err('Target comment cannot be found!');
            
            /* Check target comment exists or reply to your own comments */
            if ($target->user_id == session('user_id'))
                return err('You cannot reply to your own comment!');
            $this->reply_to = rq('reply_to');
        }


        $this->content = rq('content');
        $this->user_id = session('user_id');

        return $this->save() ?
            succ(['id' => $this->id]) :
            err('db delete fail');
    }


    /** Read comment */

    public function read()
    {
        if (!rq('question_id') && !rq('answer_id'))
            return err('Question or answer not exists!');

        if (rq('question_id'))
        {
            $question = question_init()->find(rq('question_id'));
            if (!$question)
                return err('Question not exists!');

            $data = $this->where('question_id', rq('question_id'));
        } else
        {
            $answer = question_init()->find(rq('answer_id'));
            if (!$answer)
                return err('Answer not exists!');

            $data = $this->where('answer_id', rq('answer_id'));
        }

        $data = $data->get()->keyBy('id');

        return succ(['data' => $data]);
    }


    /** Remove comment API */

    public function remove()
    {
        if (!user_init()->is_logged_in())
            return err('Please log in first!');

        if (!rq('id'))
            return err('id is required!');

        $comment = $this->find(rq('id'));
        if (!$comment)
            return err('Comment not exists!');

        if ($comment->user_id != session('user_id'))
            return err('Permission denied!');

        /* 删除此评论下所有的回复 */
        $this->where('reply_to', rq('id'))->delete();
        
        return $comment->delete() ?
            suc(['id' => $comment->id]) :
            err('db delete fail');
    }

}
