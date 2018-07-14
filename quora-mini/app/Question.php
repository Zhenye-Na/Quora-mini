<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    /** Create question API */

    public function add()
    {
        
        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return ['status' => 0, 'msg' => 'Please log in first!'];

        /* Check whether there exists question title */
        if (!rq('title'))
            return ['status' => 0, 'msg' => 'Qustion title is required!'];

        $this->title = rq('title');
        $this->user_id = session('user_id');
        
        
        /* Add question description if exists */
        if (rq('desc'))
            $this->desc  = rq('desc');

        /* Save to database */
        return $this->save() ?
            ['status' => 1, 'id' => $this->id]:
            ['status' => 0, 'msg' => 'DB insert failed!'];
        
    }


    /** Update question API */

    public function change()
    {
        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return ['status' => 0, 'msg' => 'Please log in first!'];

        /* Check whether id exists */
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'User id is required!'];

        /* Get particular model from 'id' */
        $question = $this->find(rq('id'));

        /* Check whether question exists */
        if (!$question)
            return ['status' => 0, 'msg' => 'Question not exists!'];

        if ($question->user_id != session('user_id'))
            return ['status' => 0, 'msg' => 'Permission Denied!'];


        if (rq('title'))
            $question->title = rq('title');

        if (rq('dsec'))
            $question->desc = rq('desc');


        /* Save to database */
        return $this->save() ?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'DB update failed!'];

    }


















}
