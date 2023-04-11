<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\Core\Issue as Issue;
use App\Models\Core\IssueType as IssueType;

class UserIssueService extends Api
{

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all issue rows
             *
             * @author  William Novak
             * @date    2017-03-29
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'   => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message("user", "not-found")
                        ]
                    );
                }

                $issues = Issue::where('user_id', $input['user_id'])->get();

                if ($issues->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message("issue", "not-found")
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message("issue", "found"),
                        'data'    => ['user_issue' => $issues->toArray()]
                    ]
                );

            break;
        }
    }

}
