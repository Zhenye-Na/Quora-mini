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


//        $answers = $answers->sortByDesc(function($item) {
//            return $item->created_at;
//        });
//
//        $questions = $questions->sortByDesc(function($item) {
//            return $item->created_at;
//        });


        /* Merge questions and answers */
        $data = $questions->toBase()->merge($answers);

        /* Sort by Created time */
        $data = $data->sortByDesc(function($item) {
            return $item->created_at;
        });

//        dd($data->toArray());

        $data = $data->values()->all();

        return $data;
//        return succ(['questions' => $questions, 'answers' => $answers, 'data' => $data]);
    }
}
