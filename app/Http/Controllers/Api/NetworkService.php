<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise as Enterprise;
use App\Models\Person as Person;
use App\Models\User as User;

use App\Models\EnterpriseNetwork as EnterpriseNetwork;
use App\Models\PersonNetwork as PersonNetwork;
use App\Models\UserNetwork as UserNetwork;

use Carbon\Carbon;

class NetworkService extends Api
{

	public function create(Request $request)
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
                    'for'   => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'    => ['required', 'numeric'],
                    'network'   => ['required', 'max:224'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person, user or enterprise",
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

                    case 'user':

                        $user = Enterprise::find($input['id']);

                        if ($user == null)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user', 'not-found')
                                ]
                            );
                        }

                        $check = UserNetwork::check($input['network']);
                        if ($check == false)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('network', 'exists')
                                ]
                            );
                        }

                        UserNetwork::create(
                            [
                                'network'         => (string) trim($input['network']),
                                'is_active'     => true,
                                'enterprise_id' => (int) $enterprise->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('network', 'created')
                            ]
                        );

                    break;

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

                        $check = EnterpriseNetwork::check($input['network']);
                        if ($check == false)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('network', 'exists')
                                ]
                            );
                        }

                        EnterpriseNetwork::create(
                            [
                                'network'         => (string) trim($input['network']),
                                'is_active'     => true,
                                'enterprise_id' => (int) $enterprise->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('network', 'created')
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

                        $check = PersonNetwork::check($input['network']);
                        if ($check == false)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('network', 'exists')
                                ]
                            );
                        }

                        PersonNetwork::create(
                            [
                                'network'     => (string) trim($input['network']),
                                'is_active' => true,
                                'person_id' => (int) $person->id
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('network', 'created')
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
                    'for'       => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'        => ['required', 'numeric'],
                    'network'   => ['required', 'max:224'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person, user or enterprise",
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

                        UserNetwork::find($input['id'])->update(
                            [
                                'network'         => (string) trim($input['network']),
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('network', 'updated')
                            ]
                        );

                    break;

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

                        EnterpriseNetwork::find($input['id'])->update(
                            [
                                'network'         => (string) trim($input['network']),
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('network', 'updated')
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

                        PersonNetwork::find($input['id'])->update(
                            [
                                'network'     => (string) trim($input['network']),
                            ]
                        );

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message('network', 'updated')
                            ]
                        );

                    break;

                }

            break;
        }
    }

    public function get(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @date 03/12/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'for'   => ['required', 'alpha', 'in:person,enterprise,user'],
                    'id'    => ['required', 'numeric'],
                ];
                # define messages
                $messages = [
                    'in'          => "The ':attribute' field need be just person, user or enterprise",
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

                        $data = UserNetwork::where('enterprise_id', $input['id'])->get();
                        
                        if ($data->count() == 0)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('network', 'not-found')
                                ]
                            );                            
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('network', 'total-found', ['total' => $data->count()]),
                                'data'      => $data->toArray()
                            ]
                        );

                    break;

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

                        $data = EnterpriseNetwork::where('enterprise_id', $input['id'])->get();
                        
                        if ($data->count() == 0)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('network', 'not-found')
                                ]
                            );                            
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('network', 'total-found', ['total' => $data->count()]),
                                'data'      => $data->toArray()
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

                        $data = PersonNetwork::where('person_id', $input['id'])->get();
                        
                        if ($data->count() == 0)
                        {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('network', 'not-found')
                                ]
                            );                            
                        }

                        return response()->json(
                            [
                                'status'    => true,
                                'message'   => message('network', 'total-found', ['total' => $data->count()]),
                                'data'      => $data->toArray()
                            ]
                        );


                    break;

                }

            break;
        }
    }

}
