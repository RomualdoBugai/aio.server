<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User as User;
use App\Models\FollowUp\FollowUp as FollowUp;
use App\Models\FollowUp\EnterpriseFollowUp as EnterpriseFollowUp;
use App\Models\FollowUp\Attachment as Attachment;

class AttachmentService extends Api
{

    public function create(Request $request)
    {
        switch ($request->version) {
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'description'   => ['required'],
                    'for'           => ['required', 'in:enterprise'],
                    'controller_id' => ['required', 'numeric'],
                    'user_id'       => ['required', 'numeric']
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
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $model      = \App\Models\Enterprise::class;
                        $enterprise = $model::find($input['controller_id']);

                        if ($enterprise == null) {
                            return response()
                            ->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found')
                                ]
                            );
                        }

                    break;
                }

                $followUp = FollowUp::create(
                    [
                        'description'   => (string) $input['description'],
                        'user_id'       => (int)    $input['user_id']
                    ]
                );

                foreach($request->file('file') as $file) {

                    $ext            = $file->getClientOriginalExtension();
                    $originalName   = $file->getClientOriginalName();
                    $size           = $file->getClientSize();
                    $name           = substr(md5($originalName . uniqid(rand(), true)), 0, 16) . '.' . $ext;
                    $path           = implode('/', [public_path(), 'storage', 'attachment']);
                    $file->move($path, $name);

                    $attachment     = Attachment::create(
                        [
                            'name'          => $originalName,
                            'filename'      => $name,
                            'format'        => $ext,
                            'size'          => $size,
                            'path'          => $path,
                            'follow_up_id'  => $followUp->id
                        ]
                    );

                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $enterpriseFollowUp = EnterpriseFollowUp::create(
                            [
                                'enterprise_id' => $enterprise->id,
                                'follow_up_id'  => $followUp->id
                            ]
                        );

                        return response()
                        ->json(
                            [
                                'status'  => true,
                                'message' => message('attachment', 'created'),
                                'data'    => [
                                    'follow_up'  => array_merge(
                                        $followUp->getAttributes(),
                                        ['user' => $followUp->user->getAttributes()]
                                    ),
                                    'attachment' => $followUp->attachments->toArray()
                                ]
                            ]
                        );

                    break;
                }



            break;

        }
    }

    public function one(Request $request)
    {
        switch ($request->version) {
            /**
             * create follow_up
             *
             * @author  William Novak
             * @date    2017-03-20
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'id'         => ['required', 'numeric'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $follow_up = FollowUp::find($input['id']);

                if ($follow_up == null) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('attachment', 'not-found')
                        ]
                    );
                }

                $attachment = $follow_up->attachments;

                if ($attachment->count() == 0) {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('attachment', 'attachment.not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('attachment', 'found'),
                        'data'    => [
                            'follow_up'  => array_merge(
                                $follow_up->getAttributes(),
                                ['user' => $follow_up->user->getAttributes()]
                            ),
                            'attachment' => $attachment->toArray()
                        ]
                    ]
                );

            break;
        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * create follow up
             *
             * @author  William Novak
             * @date    2017-03-20
             * @return  object json
             * @version 1.0
             */
            case '1.0':

                $input = $request->input();

                # define rules
                $rules = [
                    'for'           => ['required', 'in:enterprise'],
                    'id'            => ['required', 'numeric']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $model      = \App\Models\Enterprise::class;
                        $enterprise = $model::find($input['id']);

                        if ($enterprise == null)
                        {
                            return response()
                            ->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found')
                                ]
                            );
                        }

                    break;
                }

                switch ($input['for']) {

                    # enterprise
                    case 'enterprise':

                        $enterpriseFollowUp = EnterpriseFollowUp::with('followUp')
                        ->where('enterprise_id', $enterprise->id)
                        ->get();

                        if ($enterpriseFollowUp->count() == 0) {
                            return response()
                            ->json(
                                [
                                    'status'  => false,
                                    'message' => message('attachment', 'not-found')
                                ]
                            );
                        }

                        $result = [];

                        foreach($enterpriseFollowUp as $followUp) {
                            $result[]   = array_merge(
                                ['follow_up'    => $followUp->followUp->getAttributes()],
                                ['user'         => $followUp->followUp->user->getAttributes()],
                                ['attachment'   => $followUp->followUp->attachments->toArray()]
                            );
                        }

                        return response()
                        ->json(
                            [
                                'status'  => true,
                                'message' => message('attachment', 'found'),
                                'data'    => ['enterprise_attachment' => $result]
                            ]
                        );

                    break;
                }

            break;

        }
    }

}
