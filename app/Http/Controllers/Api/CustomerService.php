<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise as Enterprise;
use App\Models\Person as Person;
use Carbon\Carbon;

class CustomerService extends Api
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
                    'for'           => ['required', 'in:customer,enterprise'],
                    'national_code' => ['required', 'exists:customer,!national_code', 'exists:enterprise,!national_code'],
                ];
                # define messages
                $messages = [
                    'required'  => "The ':attribute' field is required",
                    'exists'    => "The ':attribute' already exists",
                    'in'        => "The value of ':attribute' need be customer or enterprise",
                ];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false)
                {
                    return response()
                    ->json($validate);
                }

                $customer = null;

                if ( ($input['person'] == true && $input['enterprise'] == true) || ($input['person'] == false && $input['enterprise'] == false)) 
                {
					return response()->json(
	                    [
	                        'status'  => false,
	                        'message' => 'set just one type of relation'
	                    ]
	                );
                }

                $data = [];

                if ($input['person'] == true && $input['enterprise'] == false)
                {
                	$person = Person::where('national_code', $input['national_code'])->first();
                	if ($person == null)
                	{
                		return response()->json(
		                    [
		                        'status'  => false,
		                        'message' => 'person not found'
		                    ]
		                );
                	}

                	$data = [
            			'person_id'     => $person->id,
            			'enterprise_id' => null
            		];
                }

                if ($input['person'] == false && $input['enterprise'] == true)
                {
                	$enterprise = Enterprise::where('national_code', $input['national_code'])->first();
                	if ($enterprise == null)
                	{
                		return response()->json(
		                    [
		                        'status'  => false,
		                        'message' => 'enterprise not found'
		                    ]
		                );

                        $data = [
                            'person_id'     => $enterprise->id,
                            'enterprise_id' => null
                        ];
                	}
                }

                $customer = Customer::create($data);
                $customer = Customer::find($customer->id);

                if ($customer == null)
                {
					return response()->json(
                    	[
	                        'status'  => false,
	                        'message' => 'a error has ocurred, try again'
	                    ]
	                );                	
                }

                return response()->json(
                    [
                        'status'  => true,
                        'message' => 'done',
                        'data'    => $customer->getAttributes()
                    ]
                );

            break;
        }
    }
    
}	