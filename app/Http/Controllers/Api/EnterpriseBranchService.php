<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Enterprise       as Enterprise;
use App\Models\EnterpriseBranch as EnterpriseBranch;


class EnterpriseBranchService extends Api
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
                    'matrix_enterprise_id' => ['required', 'exists:enterprise,id'],
                    'branch_enterprise_id' => ['required', 'exists:enterprise,id'],
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

                $check = EnterpriseBranch::where('branch_enterprise_id', $input['branch_enterprise_id'])->first();

                if ($check != null)
                {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'exists')
                        ]
                    );
                }

                $enterprise = EnterpriseBranch::create(
                    [
                        'matrix_enterprise_id'  => (int) $input['matrix_enterprise_id'],
                        'branch_enterprise_id'  => (int) $input['branch_enterprise_id'],
                    ]
                );

                $enterprise = Enterprise::find($enterprise->id);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message('enterprise', 'created')
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
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'branch_enterprise_id' => ['required', 'exists:enterprise_branch,branch_enterprise_id'],
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

                $matrix = EnterpriseBranch::where('branch_enterprise_id', $input['branch_enterprise_id'])->first();
                $enterprise = Enterprise::find($matrix->matrix_enterprise_id);

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('enterprise', 'exists'),
                        'data'      => [
                            'enterprise' => $enterprise->getAttributes()
                        ]
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
             * @author Romualdo Bugai
             * @date 26/04/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();
                # define rules
                $rules = [
                    'matrix_enterprise_id'  => ['required', 'exists:enterprise_branch,matrix_enterprise_id'],
                    'addresses'             => ['required', 'boolean'],
                    'emails'                => ['required', 'boolean'],
                    'phones'                => ['required', 'boolean'],
                    'additional'            => ['required', 'boolean'],
                    'certificates'          => ['required', 'boolean'],
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

                $enterprises     = EnterpriseBranch::where('matrix_enterprise_id', $input['matrix_enterprise_id'])->get();

                if ($enterprises->count() == 0)
                {
                    return response()->json(
                        [
                            'status'    => false,
                            'message'   => message('enterprise', 'not-found')
                        ]
                    );
                }

                $enterprise = [];

                foreach($enterprises as $branch)
                {
                    $ent = Enterprise::where('status', (bool)   true)
                    ->find($branch->branch_enterprise_id);

                    if($ent != null) {

                        $data   = $ent->getAttributes();

                        if ($input['addresses'])
                        {
                            $data['addresses'] = $ent->addresses;
                        }

                        if ($input['phones'])
                        {
                            $data['phones'] = $ent->phones;
                        }

                        if ($input['emails'])
                        {
                            $data['emails'] = $ent->emails;
                        }

                        if ($input['additional'])
                        {
                            $data['additional'] = $ent->additional;
                        }

                        if ($input['certificates'])
                        {
                            $data['certificates'] = $ent->certificates;
                        }

                        $enterprise[] = $data;
                        unset($data);
                    }       

                }

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('enterprise-total', 'found', ['total' => count($enterprise)]),
                        'data'      => $enterprise
                    ]
                );

            break;

            /**
             * @author William Novak
             * @date   2017-04-06
             * @return void
             */
            case '1.1':
                $input = $request->input();
                # define rules
                $rules = [
                    'branch_enterprise_id'  => ['required', 'exists:enterprise_branch,branch_enterprise_id'],
                    'addresses'             => ['required', 'boolean'],
                    'emails'                => ['required', 'boolean'],
                    'phones'                => ['required', 'boolean'],
                    'additional'            => ['required', 'boolean'],
                    'certificates'          => ['required', 'boolean'],
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()
                    ->json($validate);
                }

                $enterprises     = EnterpriseBranch::where('branch_enterprise_id', $input['branch_enterprise_id'])->get();

                if ($enterprises->count() == 0) {
                    return response()->json(
                        [
                            'status'    => false,
                            'message'   => message('enterprise', 'not-found')
                        ]
                    );
                }

                $enterprise = [];

                foreach($enterprises as $branch) {
                    $ent    = Enterprise::find($branch->matrix_enterprise_id);
                    $data   = $ent->getAttributes();

                    if ($input['addresses']) {
                        $data['addresses'] = $ent->addresses;
                    }

                    if ($input['phones']) {
                        $data['phones'] = $ent->phones;
                    }

                    if ($input['emails']) {
                        $data['emails'] = $ent->emails;
                    }

                    if ($input['additional']) {
                        $data['additional'] = $ent->additional;
                    }

                    if ($input['certificates']) {
                        $data['certificates'] = $ent->certificates;
                    }

                    $enterprise[] = $data;
                    unset($data);

                }

                return response()->json(
                    [
                        'status'    => true,
                        'message'   => message('enterprise-total', 'found', ['total' => count($enterprise)]),
                        'data'      => [
                            'enterprise' => $enterprise[0]
                        ]
                    ]
                );

            break;
        }
    }

}
