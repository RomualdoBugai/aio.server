<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Person           as Person;
use App\Models\PersonAddress    as PersonAdress;
use App\Models\PersonNetwork    as PersonNetwork;
use App\Models\PersonPhone      as PersonPhone;
use App\Models\PersonEmail      as PersonEmail;

class PersonService extends Api
{

    public function create(Request $request)
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
                    'name'          => ['required', 'max:96'],
                    'alias'         => ['required', 'max:96'],
                    'description'   => ['max:255'],
                    'national_code' => ['required', 'max:11', 'unique:person,national_code'],
                    'gender'        => ['required',  'in:f,m,i']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::create(
                    [
                        'name'          => (string) trim(strtolower($input['name'])),
                        'alias'         => (string) trim(strtolower($input['alias'])),
                        'national_code' => (string) trim(strtolower($input['national_code'])),
                        'description'   => (string) ( isset($input['description']) ? trim(strtolower($input['description'])) : '' ),
                        'gender'        => (string) strtolower($input['gender']),
                        'is_active'     => (bool)   true
                    ]
                );

                $person = Person::find($person->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'created'),
                        'data'    => $person->getAttributes()
                    ]
                );

            break;
            /**
             * @author William Novak
             * @date 2016-02-13
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'name' => ['required', 'max:96']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::create(
                    [
                        'name'          => (string) trim(strtolower($input['name'])),
                        'alias'         => (string) trim(strtolower($input['name'])),
                        'is_active'     => (bool)   true
                    ]
                );

                $person = Person::find($person->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'created'),
                        'data'    => $person->getAttributes()
                    ]
                );

            break;
        }
    }

    public function disable(Request $request)
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
                    'id'            => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::find($input['id']);

                if ($person == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'not-found')
                        ]
                    );
                }

                if ($person->is_active == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'no-disable')
                        ]
                    );
                }

                $person->update(
                    [
                        'is_active' => 0
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'deactivated')
                    ]
                );

            break;
        }
    }

    public function enable(Request $request)
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
                    'id'            => ['required'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::find($input['id']);

                if ($person == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'not-found')
                        ]
                    );
                }

                if ($person->is_active == 1)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'no-enable')
                        ]
                    );
                }

                $person->update(
                    [
                        'is_active' => 1
                    ]
                );

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'activated')
                    ]
                );

            break;
        }
    }

    public function update(Request $request)
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

                if (!isset($input['id']))
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'empty-id')
                        ]
                    );
                }

                # define rules
                $rules = [
                    'id'            => ['required'],
                    'name'          => ['required',  'max:96'],
                    'alias'         => ['required',  'max:96'],
                    'description'   => ['max:255'],
                    'national_code' => ['required',  'max:11', 'unique:person,national_code,' . $input['id']],
                    'gender'        => ['required', 'in:f,m,i']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::find($input['id']);

                if ($person == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'not-found')
                        ]
                    );
                }

                $person = $person->update(
                    [
                        'name'          => (string) trim(strtolower($input['name'])),
                        'alias'         => (string) trim(strtolower($input['alias'])),
                        'national_code' => (string) trim(strtolower($input['national_code'])),
                        'description'   => (string) trim(strtolower($input['description'])),
                        'gender'        => (string) strtolower($input['gender']),
                    ]
                );

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'updated')
                    ]
                );

            break;
        }
    }

    public function one(Request $request)
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
                    'id'            => ['required'],
                    'addresses'     => ['required', 'boolean'],
                    'emails'        => ['required', 'boolean'],
                    'phones'        => ['required', 'boolean'],
                    'networks'      => ['required', 'boolean']
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::find($input['id']);

                if ($person == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'not-found')
                        ]
                    );
                }

                $data['person'] = $person->getAttributes();

                if ($input['addresses'])
                {
                    $data['addresses'] = $person->addresses;
                }

                if ($input['phones'])
                {
                    $data['phones'] = $person->phones;
                }

                if ($input['emails'])
                {
                    $data['emails'] = $person->emails;
                }

                if ($input['networks'])
                {
                    $data['networks'] = $person->networks;
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'found'),
                        'data'    => $data
                    ]
                );

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
                    'limit'     => ['int'],
                    'offset'    => ['int'],
                ];

                # define messages
                $messages = [];

                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $person = Person::get();

                if ($person->count() == 0)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('person', 'not-found')
                        ]
                    );
                }

                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('person', 'total-found', ['total' => $person->count()]),
                        'data'    => $person
                    ]
                );

            break;
        }
    }

}
