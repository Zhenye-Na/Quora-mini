<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
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





















}
