<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /** Create Answer API */
    
    public function add()
    {
        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return ['status' => 0, 'msg' => 'Please log in first!'];
        
        /* check question_id and content of answer are included in arguments */
        if (!rq('question_id') || !rq('content'))
            return ['status' => 0, 'msg' => 'question_id and answer are required!'];
        
        /* Check whether this question exists or not */
        $question = question_init()->find(rq('question_id'));
        if (!$question)
            return ['status' => 0, 'msg' => 'Question not exists!'];
        
        /* Check this user has already answered this question or not */
        $answered = $this
            ->where(['question_id' => rq('question_id'), 'user_id' => session('user_id')])
            ->count();
        
        /* Duplicate answers */
        if ($answered)
            return ['status' => 0, 'msg' => 'You have already answered this question!'];
        
        /* Get variables */
        $this->content = rq('content');
        $this->question_id = rq('question_id');
        $this->user_id = session('user_id');
        
        /* Save to database */
        return $this->save() ?
            ['status' => 1, 'id' => $this->id] :
            ['status' => 0, 'msg' => 'db insert failed!'];
        
    }


    /** Update answer API */

    public function change()
    {
        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return ['status' => 0, 'msg' => 'Please log in first!'];


        if (!rq('id') || !rq('content'))
            return ['status' => 0, 'msg' => 'id and content are required!'];


        $answer = $this->find(rq('id'));
        if ($answer->user_id != session('user_id'))
            return ['status' => 0, 'msg' => 'Permission denied!'];


        $answer->content = rq('content');

        return $answer->save() ?
            ['status' => 1] :
            ['status' => 0, 'msg' => 'db update failed!'];

    }

    
    /** Read answer API */

    public function read()
    {
        /* Check answer id and question id */
        if ((!rq('id')) && !rq('question_id'))
            return ['status' => 0, 'msg' => 'id or question_id is required!'];
        
        /* Check whether this answer exists return error message if not */
        if (rq('id'))
        {
            $answer = $this->find(rq('id'));
            
            if (!$answer)
                return ['status' => 0, 'msg' => 'Answer not exists!'];
            return ['status' => 1, 'data' => $answer];
        }
        
        /* Check whether this question exists return error message if not */
        if (!question_init()->find(rq('question_id')))
            return ['status' => 0, 'msg' => 'Question not exists!'];
        
        $answers = $this
            ->where('question_id', rq('question_id'))
            ->get()
            ->keyBy('id');
        
        
        return ['status' => 1, 'data' => $answers];
    }

    
}
