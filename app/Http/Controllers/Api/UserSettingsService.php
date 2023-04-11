<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\User         as User;
use App\Models\UserSettings as UserSettings;

class UserSettingsService extends Api
{

    public function one(Request $request)
    {
        switch ($request->version)
        {
            /**
             * @author William Novak
             * @return void
             * @version 1.0 2016-01-08
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'    => ['required']
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

                $user = User::find($input['id']);

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $userSettings = UserSettings::where("user_id", $user->id)->first();

                if ($userSettings == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user-settings', 'not-found')
                        ]
                    );
                }


                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-settings', 'found'),
                        'data'    => [
                            'user_settings' => $userSettings->getAttributes()
                        ]
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
             * @return void
             * @version 1.0 2016-01-08
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'                => ['required'],
                    'timezone'          => ['required'],
                    'date_format'       => ['required'],
                    'input_date_format' => ['required']
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

                $user = User::find($input['id']);

                if ($user == null)
                {
                    return response()
                    ->json(
                        [
                            'status'  => false,
                            'message' => message('user', 'not-found')
                        ]
                    );
                }

                $userSettings = UserSettings::where("user_id", $user->id)->first();

                if ($userSettings == null)
                {
                    UserSettings::create(
                        [
                            'user_id'           => (int)    $user->id,
                            'timezone'          => (string) $input['timezone'],
                            'date_format'       => (string) $input['date_format'],
                            'input_date_format' => (string) $input['input_date_format']
                        ]
                    );

                    return response()
                    ->json(
                        [
                            'status'  => true,
                            'message' => message('user-settings', 'created')
                        ]
                    );

                }

                $userSettings->update(
                    [
                        'timezone'          => (string) $input['timezone'],
                        'date_format'       => (string) $input['date_format'],
                        'input_date_format' => (string) $input['input_date_format']
                    ]
                );


                return response()
                ->json(
                    [
                        'status'  => true,
                        'message' => message('user-settings', 'updated')
                    ]
                );

            break;
        }
    }


}
