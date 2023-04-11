<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\FollowUp\FollowUpReason as FollowUpReason;

class FollowUpReasonService extends Api
{

    public function get(Request $request)
    {
        switch ($request->version)
        {
            /**
             * retrive all follow up reason rows
             *
             * @author  William Novak
             * @date    08/12/2016
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $reason = new FollowUpReason;
                $reason = $reason->get();

                if ($reason->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('follow-up', 'follow-up-reason-not-found')
                        ]
                    );
                }

                $data = [];
                foreach($reason as $key => $value)
                {
                    $data[] = ['id' => $value['id'], 'name' => message('follow-up-reason', $value['name'])];
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('follow-up', 'total-found', ['total' => $reason->count()]),
                        'data'    => $data
                    ]
                );

            break;
        }
    }

}
