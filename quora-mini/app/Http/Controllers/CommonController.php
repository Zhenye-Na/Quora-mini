<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    /** Timeline API */

    public function timeline()
    {
        list($limit, $skip) = paginate(rq('page'), rq('limit'));

        /* Retrieve questions */
        $questions = question_init()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        /* Retrieve answers */
        $answers = answer_init()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();        

        /* Merge questions and answers */
        $data = $questions->merge($answers);

        /* Sort by Created time */
        $data = $data->sortByDesc(function($item) {
            return $item->created_at;
        });

        $data = $data->values()->all();

        return ['status' => 1, 'data' => $data];
    }
}
