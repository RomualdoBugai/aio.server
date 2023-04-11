<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise as Enterprise;
use App\Models\Person as Person;

use App\Models\EnterpriseEmail as EnterpriseEmail;
use App\Models\PersonEmail as PersonEmail;

use Carbon\Carbon;
use App\Services\Log as Log;

class EmailService extends Api
{

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
                    'for'   => ['required', 'alpha', 'in:person,enterprise'],
                    'id'    => ['required', 'numeric'],
                    'email' => ['required', 'email'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                switch ($input['for']) {
                    case 'enterprise':

                        $enterprise = Enterprise::find($input['id']);

                        if ($enterprise == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found')
                                ]
                            );
                        }

                        $check = EnterpriseEmail::check($input['email']);
                        if ($check == false)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'exists')
                                ]
                            );
                        }

                        $data = EnterpriseEmail::create(
                            [
                                'email'         => (string) trim($input['email']),
                                'is_active'     => true,
                                'enterprise_id' => (int) $enterprise->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('email', 'created')
                            ]
                        );

                    break;

                    case 'person':

                        $person = Person::find($input['id']);

                        if ($person == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('person', 'not-found')
                                ]
                            );
                        }

                        $check = PersonEmail::check($input['email']);
                        if ($check == false)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'exists')
                                ]
                            );
                        }

                        PersonEmail::create(
                            [
                                'email'     => (string) trim($input['email']),
                                'is_active' => true,
                                'person_id' => (int) $person->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('email', 'created')
                            ]
                        );

                    break;

                }

            break;

            /**
             * @author William Novak
             * @date 2017-02-19
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'       => ['required', 'alpha', 'in:person,enterprise'],
                    'id'        => ['required', 'numeric'],
                    'email'     => ['required', 'email'],
                    'user_id'   => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $user = \App\Models\User::find($input['id']);

                if ($user == null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                switch ($input['for'])
                {
                    case 'enterprise':

                        $enterprise = Enterprise::find($input['id']);

                        if ($enterprise == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found')
                                ]
                            );
                        }

                        $check = EnterpriseEmail::check($input['email']);
                        if ($check == false)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'exists')
                                ]
                            );
                        }

                        $data = EnterpriseEmail::create(
                            [
                                'email'         => (string) trim($input['email']),
                                'is_active'     => true,
                                'enterprise_id' => (int) $enterprise->id
                            ]
                        );

                        $app = $input['app'];

                        $log = Log::save([
                            'app_id'            => $app->id,
                            'enterprise_id'     => $enterprise->id,
                            'user_id'           => $user->id,
                            'table'             => 'enterprise_email',
                            'table_id'          => $data->id,
                            'message'           => json_encode(['message' => 'created-email', 'email' => $data->email])
                        ], 'enterprise');

                        return response()
                        ->json(
                            [
                                'status'  => true,
                                'message' => message('email', 'created')
                            ]
                        );

                    break;

                }

            break;

        }
    }

    public function update(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'   => ['required', 'alpha', 'in:person,enterprise'],
                    'id'    => ['required', 'numeric'],
                    'email' => ['required', 'email'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                switch ($input['for']) {
                    case 'enterprise':

                        $enterpriseEmail = EnterpriseEmail::find($input['id']);

						if ($enterpriseEmail == null) {
							return response()->json(
								[
									'status'  => false,
									'message' => message('email', 'not-found')
								]
							);
						}

						$from = $enterpriseEmail->email;
						$to   = trim($input['email']);

                        if ($from == $to) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'the-same')
                                ]
                            );
                        }

                        $enterpriseEmail->update(
                            [
                                'email'     => (string) trim($input['email'])
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('email', 'updated')
                            ]
                        );

                    break;

                    case 'person':

                        $personEmail = PersonEmail::find($input['id']);

                        if ($personEmail == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'not-found')
                                ]
                            );
                        }

                        $from = $personEmail->email;
                        $to   = trim($input['email']);

                        if ($from == $to) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'the-same')
                                ]
                            );
                        }

                        $personEmail->update(
                            [
                                'email'     => (string) trim($input['email'])
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('email', 'updated')
                            ]
                        );

                    break;

                }

            break;


            /**
             * @author William Novak
             * @date 2017-02-19
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'       => ['required', 'alpha', 'in:person,enterprise'],
                    'id'        => ['required', 'numeric'],
                    'email'     => ['required', 'email'],
                    'user_id'   => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $user = \App\Models\User::find($input['id']);

                if ($user == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                switch ($input['for']) {
                    case 'enterprise':

                        $enterpriseEmail = EnterpriseEmail::find($input['id']);

                        if ($enterpriseEmail == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'not-found')
                                ]
                            );
                        }

                        $from = $enterpriseEmail->email;
                        $to   = trim($input['email']);

                        if ($from == $to)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'the-same')
                                ]
                            );
                        }

                        $enterpriseEmail->update(
                            [
                                'email'     => (string) trim($input['email'])
                            ]
                        );

                        $app = $input['app'];

                        $log = Log::save([
                            'app_id'            => $app->id,
                            'enterprise_id'     => $enterpriseEmail->enterprise_id,
                            'user_id'           => $user->id,
                            'table'             => 'enterprise_email',
                            'table_id'          => $enterpriseEmail->id,
                            'message'           => json_encode(['message' => 'updated-email', 'from' => $from, 'to' => $to])
                        ], 'enterprise');

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('email', 'updated')
                            ]
                        );

                    break;

                }

            break;
        }
    }

    public function get(Request $request)
    {
        switch ($request->version) {
            /**
             * @author William Novak
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'   => ['required', 'alpha', 'in:person,enterprise'],
                    'id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                switch ($input['for'])
                {
                    case 'enterprise':

                        $enterprise = Enterprise::find($input['id']);

                        if ($enterprise == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found')
                                ]
                            );
                        }

                        $data = EnterpriseEmail::where('enterprise_id', $input['id'])->get();

                        if ($data->count() == 0) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('email', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'enterprise_email' => $data->toArray()
                                ]
                            ]
                        );

                    break;

                    case 'person':

                        $person = Person::find($input['id']);

                        if ($person == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('person', 'not-found')
                                ]
                            );
                        }

                        $data = PersonEmail::where('person_id', $input['id'])->get();

                        if ($data->count() == 0) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('email', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'person_email' => $data->toArray()
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
             * @author William Novak
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'   => ['required', 'alpha', 'in:person,enterprise'],
                    'id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                switch ($input['for'])
                {
                    case 'enterprise':

                        $enterpriseEmail = EnterpriseEmail::find($input['id']);

                        if ($enterpriseEmail == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('email', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'enterprise_email' => $enterpriseEmail->getAttributes()
                                ]
                            ]
                        );

                    break;

                    case 'person':

                        $personEmail = PersonEmail::find($input['id']);

                        if ($personEmail == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('email', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('email', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'person_email' => $personEmail->getAttributes()
                                ]
                            ]
                        );

                    break;

                }

            break;
        }
    }

}
