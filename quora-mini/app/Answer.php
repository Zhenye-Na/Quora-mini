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
            return err('Please log in first!');


        if (!rq('id') || !rq('content'))
            return err('id and content are required!');


        $answer = $this->find(rq('id'));
        if ($answer->user_id != session('user_id'))
            return err('Permission denied!');


        $answer->content = rq('content');

        return $answer->save() ?
            succ(['id' => $answer->id]) :
            err('db update fail');

    }


    /** Read by user id */
    public function read_by_user_id($user_id) {
        $user = user_init()->find($user_id);

        if (!$user) {
            return err('Cannot find this user.');
        }

        $result = $this
            ->with('question')
            ->where('user_id', $user_id)
            ->get()
            ->keyBy('id');

        return succ($result->toArray());
    }


    /** Read answer API */

    public function read()
    {
        /* Check answer id and question id */
        if ((!rq('id')) && !rq('question_id') && !rq('user_id'))
            return err('id, question_id or user_id is required!');

        if (rq('user_id')) {
            $user_id = rq('user_id') === 'self' ?
                session('user_id'):
                rq('user_id');

            return $this->read_by_user_id($user_id);
        }

        /* Check whether this answer exists return error message if not */
        if (rq('id'))
        {
            $answer = $this
                ->with('user')
                ->with('users')
                ->find(rq('id'));
            
            if (!$answer)
                return err('Answer not exists!');
            return succ(['data' => $answer]);
        }
        
        /* Check whether this question exists return error message if not */
        if (!question_init()->find(rq('question_id')))
            return err('Question not exists!');
        
        $answers = $this
            ->where('question_id', rq('question_id'))
            ->get()
            ->keyBy('id');
        
        
        return succ(['data' => $answers]);
    }

    
    /** Vote API */

    public function vote()
    {
        if (!user_init()->is_logged_in())
            return err('Please log in first!');
        
        
        if (!rq('id') || !rq('vote'))
            return err('id or vote is required!');
        
        /* Check whether this user voted this question before */

        $answer = $this->find(rq('id'));
        if (!$answer)
            return err('Answer not exists!');

        /* Up-vote or Down-vote*/
        $vote = rq('vote');
        
        if ($vote != 1 && $vote != 2 && $vote != 3) {
            return err('Invalid vote!');
        }
        

        /* 如果投过票, 就删除此投票, 并且更新结果 */
        $answer
            ->users()
            ->newPivotStatement()
            ->where('user_id', session('user_id'))
            ->where('answer_id', rq('id'))
            ->delete();


        if ($vote == 3) {
            return succ();
        }
        
        
        $answer
            ->users()
            ->attach(session('user_id'), ['vote' => $vote]);

        return succ();
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    
    public function users()
    {
        return $this
            ->belongsToMany('App\User')
            ->withPivot('vote')
            ->withTimestamps();
    }


    public function question() {
        return $this->belongsTo('App\Question');
    }
}
