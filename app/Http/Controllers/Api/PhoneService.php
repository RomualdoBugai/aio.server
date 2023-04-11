<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise as Enterprise;
use App\Models\Person as Person;
use App\Models\User as User;

use App\Models\EnterprisePhone as EnterprisePhone;
use App\Models\PersonPhone as PersonPhone;
use App\Models\UserPhone as UserPhone;

use Carbon\Carbon;

class PhoneService extends Api
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
                    'for'                => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'                 => ['required', 'numeric'],
                    'international_code' => ['required', 'numeric', 'max:9999'],
                    'long_distance'      => ['required', 'numeric', 'max:9999'],
                    'number'             => ['required', 'numeric', 'max:999999999'],
                    'arm'                => ['numeric']
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person, enterprise or user",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $arm = null;

                if (isset($input['arm'])) {
                    $arm = $input['arm'];
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

                        $check = EnterprisePhone::check($input['long_distance'], $input['number']);
                        if ($check == false) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'exists')
                                ]
                            );
                        }

                        EnterprisePhone::create(
                            [
                                'international_code' => (string) trim($input['international_code']),
                                'long_distance'      => (string) trim($input['long_distance']),
                                'number'             => (string) trim($input['number']),
                                'default'            => "+" . trim($input['international_code']) . " (" . trim($input['long_distance']) . ") " . trim($input['number']),
                                'arm'                => $arm,
                                'enterprise_id'      => (int) $enterprise->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('phone', 'created')
                            ]
                        );

                    break;

                    case 'user':

                        $user = User::find($input['id']);

                        if ($user == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user', 'not-found')
                                ]
                            );
                        }

                        /*$check = UserPhone::check($input['long_distance'], $input['number']);
                        if ($check == false) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'exists')
                                ]
                            );
                        }*/

                        UserPhone::create(
                            [
                                'international_code' => (string) trim($input['international_code']),
                                'long_distance'      => (string) trim($input['long_distance']),
                                'number'             => (string) trim($input['number']),
                                'default'            => "+" . trim($input['international_code']) . " (" . trim($input['long_distance']) . ") " . trim($input['number']),
                                'arm'                => $arm,
                                'user_id'            => (int) $user->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('phone', 'created')
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

                        $check = PersonPhone::check($input['long_distance'], $input['number']);
                        if ($check == false) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'exists')
                                ]
                            );
                        }

                        PersonPhone::create(
                            [
                                'international_code' => (string) trim($input['international_code']),
                                'long_distance'      => (string) trim($input['long_distance']),
                                'number'             => (string) trim($input['number']),
                                'default'            => "+" . trim($input['international_code']) . " (" . trim($input['long_distance']) . ") " . trim($input['number']),
                                'arm'                => $arm,
                                'person_id'          => (int) $person->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('phone', 'created')
                            ]
                        );

                    break;

                }

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
                    'for'                => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'                 => ['required', 'numeric'],
                    'international_code' => ['required', 'numeric', 'max:9999'],
                    'long_distance'      => ['required', 'numeric', 'max:9999'],
                    'number'             => ['required', 'numeric', 'max:999999999'],
                    'arm'                => ['numeric']
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be juste person, enterprise or user",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $arm = null;

                if (isset($input['arm'])) {
                    $arm = $input['arm'];
                }

                switch ($input['for']) {
                    case 'enterprise':

                        $enterprisePhone = EnterprisePhone::find($input['id']);

                        if ($enterprisePhone == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        $enterprisePhone->update(
                            [
                                'international_code' => (string) trim($input['international_code']),
                                'long_distance'      => (string) trim($input['long_distance']),
                                'number'             => (string) trim($input['number']),
                                'default'            => "+" . trim($input['international_code']) . " (" . trim($input['long_distance']) . ") " . trim($input['number']),
                                'arm'                => $arm,
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('phone', 'updated')
                            ]
                        );

                    break;

                    case 'user':

                        $userPhone = UserPhone::find($input['id']);

                        if ($userPhone == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        $userPhone->update(
                            [
                                'international_code' => (string) trim($input['international_code']),
                                'long_distance'      => (string) trim($input['long_distance']),
                                'number'             => (string) trim($input['number']),
                                'default'            => "+" . trim($input['international_code']) . " (" . trim($input['long_distance']) . ") " . trim($input['number']),
                                'arm'                => $arm,
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('phone', 'updated')
                            ]
                        );

                    break;

                    case 'person':

                        $personPhone = PersonPhone::find($input['id']);

                        if ($personPhone == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        $personPhone->update(
                            [
                                'international_code' => (string) trim($input['international_code']),
                                'long_distance'      => (string) trim($input['long_distance']),
                                'number'             => (string) trim($input['number']),
                                'default'            => "+" . trim($input['international_code']) . " (" . trim($input['long_distance']) . ") " . trim($input['number']),
                                'arm'                => $arm,
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('phone', 'updated')
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'                => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'                 => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be juste person, enterprise or user",
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

                        $data = EnterprisePhone::where('enterprise_id', $input['id'])->get();

                        if ($data->count() == 0)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('phone', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'enterprise_phone' => $data->toArray()
                                ]
                            ]
                        );

                    break;

                    case 'user':

                        $user = User::find($input['id']);

                        if ($user == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user', 'not-found')
                                ]
                            );
                        }

                        $data = UserPhone::where('user_id', $input['id'])->get();

                        if ($data->count() == 0)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('phone', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'user_phone' => $data->toArray()
                                ]
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

                        $data = PersonPhone::where('person_id', $input['id'])->get();

                        if ($data->count() == 0)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('phone', 'total-found', ['total' => $data->count()]),
                                'data'      => [
                                    'person_phone' => $data->toArray()
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
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @return string  json
             * @version 1.0 2017-01-08
             */
            case '1.0':

                # from request
                $input = $request->input();
                # define rules
                $rules = [
                    'for'                => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'                 => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be juste person, enterprise or user",
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

                        $data = EnterprisePhone::find($input['id']);

                        if ($data == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('phone', 'found'),
                                'data'      => [
                                    'enterprise_phone' => $data->getAttributes()
                                ]
                            ]
                        );

                    break;

                    case 'user':

                        $data = UserPhone::find($input['id']);

                        if ($data == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('phone', 'found'),
                                'data'      => [
                                    'user_phone' => $data->getAttributes()
                                ]
                            ]
                        );

                    break;

                    case 'person':

                        $data = PersonPhone::find($input['id']);

                        if ($data == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('phone', 'not-found')
                                ]
                            );
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('phone', 'found'),
                                'data'      => [
                                    'person_phone' => $data->getAttributes()
                                ]
                            ]
                        );

                    break;

                }

            break;
        }
    }

}
