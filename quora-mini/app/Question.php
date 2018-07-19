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
            return err('Please log in first!');

        /* Check whether there exists question title */
        if (!rq('title'))
            return err('Question title is required!');

        $this->title = rq('title');
        $this->user_id = session('user_id');
        
        
        /* Add question description if exists */
        if (rq('desc'))
            $this->desc = rq('desc');

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


    /** Read question API */

    public function read()
    {
        /* Check whether 'id' is in request arguments, if true return */
        if (rq('id'))
            return ['status' => 1, 'msg' => $this->find(rq('id'))];

        /* LIMIT */
        // $limit = rq('limit')?: 15;
        
        /* SKIP */
        // $skip = (rq('page') ? rq('page') - 1 : 1) * $limit;

        list($limit, $skip) = paginate(rq('page'), rq('limit'));

        /* Construct query and return collection of data */
        $r = $this
            ->orderBy('created_at')
            ->limit($limit)
            ->skip($skip)
            ->get(['id', 'title', 'desc', 'user_id', 'created_at', 'updated_at'])
            ->keyBy('id');

        return ['status' => 1, 'msg' => $r];
    }


    /** Read question API */

    public function remove()
    {
        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return ['status' => 0, 'msg' => 'Please log in first!'];

        /* check id is included in arguments */
        if (!rq('id'))
            return ['status' => 0, 'msg' => 'ID is required!'];

        /* Get model */
        $question = $this->find(rq('id'));
        if (!$question)
            return ['status' => 0, 'msg' => 'Question not exists!'];

        /* Check current user is the owner of question or not */
        if (session('user_id') != $question->user_id)
            return ['status' => 0, 'msg' => 'Permission denied!'];

        return $question->delete() ?
            ['status' => 1]:
            ['status' => 0, 'msg' => 'db deleted failed!'];

    }

}
