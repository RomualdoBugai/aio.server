<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\Support\Issue as Issue;
use App\Models\Support\IssueType as IssueType;
use App\Models\Support\IssueStatus as IssueStatus;


use WilliamNovak\Pipefy\Pipefy as Pipefy;

class IssueService extends Api
{

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * retrive all issue rows
             *
             * @author  William Novak
             * @date    07/11/2016
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $issue = Issue::with('user')->get();

                if ($issue->count() == 0) {
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
                        'data'    => ['issue' => $issue->toArray()]
                    ]
                );

            break;

            /**
             * retrive all issue rows
             *
             * @author  William Novak
             * @date    2017-03-29
             * @return  object json
             * @version 1.0
             */
            case '1.1':

                $input = $request->input();
                # define rules
                $rules = [
                    'status'        => ['required', 'array'],
                    'limit'         => ['required', 'numeric'],
                    'offset'        => ['required', 'numeric'],
                    'count'         => ['required', 'boolean'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                if ($input['count'] == true) {

                    $count = Issue::whereIn('issue_status_id', $input['status'])->count();

                    return response()
                    ->json(
                        [
                            'status'    => true,
                            'message'   => message("issue", "found"),
                            'data'      => [
                                'issue' => (int) $count
                            ]
                        ]
                    );
                }

                $issues = Issue::with('user')
                        ->whereIn('issue_status_id', $input['status'])
                        ->skip($input['offset'])->take($input['limit'])
                        ->orderBy('id', 'desc')
                        ->get();

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
                        'data'    => [
                            'issue' => $issues->toArray()
                        ]
                    ]
                );

            break;
        }
    }

    public function create(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'name'          => ['required', 'string', 'max:224'],
                    'text'          => ['required', 'string'],
                    'user_id'       => ['required']
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

                /*
                $pipe = new \WilliamNovak\Pipefy\Pipefy;
                $pipe = $pipe->create(171435, $input['name'], $input['text']);
                dd($pipe);
                */

                if ($user== null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                # app
                $app = $input['app'];

                $issue = Issue::create(
                    [
                        'name'              => (string) trim(strtolower($input['name'])),
                        'text'              => (string) trim($input['text']),
                        'user_id'           => (int) $input['user_id'],
                        'app_id'            => (int) $app->id,
                        'issue_type_id'     => 1,
                        'is_active'         => (bool)   true,
                        'issue_status_id'   => 1
                    ]
                );

                $issue = Issue::find($issue->id);

                #
                $title  = message('issue', 'notification.user.title', ['id' => $issue->id]);
                $resume = json_decode($app->resume, true);

                $data   = [
                    'user'  => $user,
                    'app'   => $app,
                    'issue' => $issue->getAttributes(),
                    'url'   => $app->url,
                    'template'      => [
                        'title'     => $title,
                        'language'  => \App::getLocale(),
                        'footer'    => $resume[\App::getLocale()]
                    ]
                ];

                Mail::send('app.issue.notification.user.new', $data, function($message) use (&$user, &$title) {
                    $message->to($user->email, $user->name)->subject($title);
                });

                $title  = message('issue', 'notification.support.title', ['app' => $app->name, 'id' => $issue->id]);

                $supportAssistants = $app->support;

                foreach($supportAssistants as $assistent) {
                    $userAssistent = $assistent->user;
                    Mail::send('app.issue.notification.support.new', $data, function($message) use (&$userAssistent, &$title) {
                        $message->to($userAssistent->email, $userAssistent->name)->subject($title);
                    });
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message("support", "request-open-successful"),
                        'data'    => [
                            'issue'  => $issue->getAttributes(),
                            'status' => $issue->status->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'id'                => ['required'],
                    'user_id'           => ['required'],
                    'name'              => ['required', 'string', 'max:224'],
                    'text'              => ['required', 'string'],
                    'issue_type_id'     => ['required'],
                    'issue_status_id'   => ['required'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $issue = Issue::find($input['id']);

                if ($issue == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('issue', 'not-found')
                        ]
                    );
                }

                $type = IssueType::find($input['issue_type_id']);

                if ($type == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('issue', 'type-not-found')
                        ]
                    );
                }

                $user = User::find($input['user_id']);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $issue = $issue->update(
                    [
                        'name'              => (string) trim(strtolower($input['name'])),
                        'text'              => (string) trim($input['text']),
                        'user_id'           => (int) $input['user_id'],
                        'issue_type_id'     => 1,
                        'is_active'         => (bool)   true,
                        'issue_status_id'   => 1
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('issue', 'updated'),
                        'data'    => [
                            'issue'  => $issue->getAttributes(),
                            'status' => $issue->status->getAttributes()
                        ]
                    ]
                );

            break;
        }
    }

    public function one(Request $request)
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

                $input      = $request->input();

                # define rules
                $rules      = [
                    'id'    => ['required'],
                ];
                # define messages
                $messages   = [];
                # validate input from request
                $validate   = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                if ( (int) $input['id'] == 0 ) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message("issue", "not-found"),
                        ]
                    );
                }

                $issue = Issue::find($input['id']);

                if ($issue == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message("issue", "not-found"),
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message("issue", "found"),
                        'data'    => [ 'issue' => $issue->getAttributes() ]
                    ]
                );

            break;
        }
    }

}
