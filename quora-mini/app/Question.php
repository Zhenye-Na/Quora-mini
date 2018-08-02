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


        /* Add question description if exists */
        if (rq('desc'))
            $this->desc = rq('desc');

        $this->title = rq('title');
        $this->user_id = session('user_id');

        /* Save to database */
        return $this->save() ?
            succ(['id' => $this->id]) :
            err('db insert failed!');
    }


    /** Update question API */

    public function change()
    {
        /* Check whether user has logged in */
        if (!user_init()->is_logged_in())
            return err('Please log in first!');

        /* Check whether id exists */
        if (!rq('id'))
            return err('id is required!');

        /* Get particular model from 'id' */
        $question = $this->find(rq('id'));

        /* Check whether question exists */
        if (!$question)
            return err('Question not exists!');

        if ($question->user_id != session('user_id'))
            return err('Permission Denied!');


        if (rq('title'))
            $question->title = rq('title');

        if (rq('desc'))
            $question->desc = rq('desc');

        /* Save to database */
        return $question->save() ?
            succ(['id' => $question->id]) :
            err('db update failed!');

    }


    /** Read by user id */
    public function read_by_user_id($user_id) {
        $user = user_init()->find($user_id);

        if (!$user) {
            return err('Cannot find this user.');
        }

        $result = $this
            ->where('user_id', $user_id)
            ->get()
            ->keyBy('id');

        return succ($result->toArray());
    }


    /** Read question API */

    public function read()
    {
        /* Check whether 'id' is in request arguments, if true return */
        if (rq('id')) {
            $result = $this
                ->with('answers_with_user_info')
                ->find(rq('id'));
            return ['status' => 1, 'data' => $result];

        }

        if (rq('user_id')) {
            $user_id = rq('user_id') === 'self' ?
                session('user_id') :
                rq('user_id');

            return $this->read_by_user_id($user_id);
        }

        list($limit, $skip) = paginate(rq('page'), rq('limit'));

        /* Construct query and return collection of data */
        $r = $this
            ->orderBy('created_at')
            ->skip($skip)
            ->limit($limit)
            ->get(['id', 'user_id', 'desc', 'title', 'created_at', 'updated_at'])
            ->keyBy('id');

        return succ(['data' => $r]);
    }


    /** Remove question API */

    public function remove()
    {
        /* Check whether user has logged in */
        if (!is_logged_in())
            return err('Please log in first!');

        /* check id is included in arguments */
        if (!rq('id'))
            return err('ID is required!');

        /* Get model */
        $question = $this->find(rq('id'));
        if (!$question)
            return err('Question not exists!');

        /* Check current user is the owner of question or not */
        if (session('user_id') != $question->user_id)
            return err('Permission denied!');

        return $question->delete() ?
            succ(['id' => $question->id]) :
            err('db deleted failed!');

    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function answers()
    {
        return $this->hasMany('App\Answer');
    }


    public function answers_with_user_info()
    {
        return $this
            ->answers()
            ->with('user')
            ->with('users');
    }

}
