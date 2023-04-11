<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User as User;
use App\Models\Certificate as Certificate;
use App\Models\Enterprise as Enterprise;
use App\Models\UserEnterprise as UserEnterprise;
use App\Models\EnterpriseAddress as EnterpriseAddress;
use App\Models\EnterprisePhone as EnterprisePhone;
use App\Models\EnterpriseEmail as EnterpriseEmail;


use Carbon\Carbon;

use WilliamNovak\Cnpj\Cnpj as Cnpj;

class CertificateService extends Api
{

    protected static $controller = "certificate";

    public function __construct()
    {
        \App::setLocale('pt');
    }

    public function makePhoneNumber($phone = null)
    {
        if ($phone == null)
        {
            return null;
        }

        $phone = str_replace(" ", "", preg_replace("/[^0-9\s]/", "", trim($phone)));

        return [
            'international_code' => '55',
            'long_distance'      => substr($phone, 0, 2),
            'number'             => substr($phone, 2, strlen($phone)),
            'default'            => '+55' . ' (' . substr($phone, 0, 2) . ') ' . substr($phone, 2, strlen($phone))
        ];
    }

    public function create(Request $request)
    {
        \App::setLocale('pt');

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
                    'user_id'    => ['required', 'exists:user,id'],
                    'password'   => ['required'],
                    'country_id' => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                if (!$request->hasFile('certificate') || !$request->file('certificate')->isValid()) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "not-found")
                        ]
                    );
                }

                if ( strtolower($input['country_id']) != 1) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "country-not-allowed")
                        ]
                    );
                }

                $file = $request->file('certificate');
                $originalName = $file->getClientOriginalName();
                $ext   = $file->getClientOriginalExtension();
                $temp  = date('Ymdhis');
                $name  = $temp . "." . $ext;
                $path  = public_path() . '/storage/app/certificate/';
                $file->move($path, $name);
                $password = $input['password'];

                # pfx
                if($ext == 'pfx'){
                    $cmd = "openssl pkcs12 -in {$path}{$name} -nokeys -nodes -out {$path}{$temp}.crt -password pass:{$password}";
                }    			

                # cer
                if($ext == 'cer'){
                    $cmd = "openssl x509 -in {$path}{$name} -out {$path}{$temp}.crt";
                }         

    			exec($cmd);

                # export to .crt
                $data = openssl_x509_parse(file_get_contents("{$path}{$temp}.crt"));

                if ($data == false) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "invalid-password")
                        ]
                    );
                }

                $hash      = $data['hash'];
                $certificateData = json_encode($data['subject']['OU']);
                $validFrom = date('Y-m-d H:i:s', $data['validFrom_time_t']);
                $validTo   = date('Y-m-d H:i:s', $data['validTo_time_t']);

                # get data
                $cn = explode(":", $data['subject']['CN']);
                $name = $cn[0];
    			$cnpj = $cn[1];

                # use webservice to get all data
                $webservice = new Cnpj();
                $webservice->setCnpj($cnpj);
                $data       = (object) $webservice->get();

                # certificate valid?
                if($validTo < date('Y-m-d H:i:s')) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "certificate-expired")
                        ]
                    );
                } 

                # if status os query is true
                if ($data->status == false) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                $data  = (object) $data->data;

                $check = Enterprise::where("national_code", $cnpj)
                ->where('status', (bool)   true)
                ->first();

                if ($check == null) {
                    $enterprise = Enterprise::create(
                        [
                            'name'          => (string) (string) strtolower($name),
                            'fantasy_name'  => (string) (string) strtolower($data->fantasia),
                            'national_code' => (string) (string) $cnpj,
                            'legal_nature'  => (string) strtolower($data->natureza_juridica),
                            'is_matrix'     => (bool) ( strtolower($data->tipo) == "matriz" ? true : false),
                            'open_at'       => (string) $data->abertura,
                            'last_update'   => (string) $data->ultima_atualizacao,
                            'is_active'     => (bool)   true,
                            'status'        => (bool) ( strtolower($data->situacao) == 'ativa' ? true : false ),
                        ]
                    );
                    $enterprise = Enterprise::find($enterprise->id);
                } else {
                    $enterprise = $check;
                }

                $certificate = Certificate::where('hash', $hash)
                ->where('enterprise_id', $enterprise->id)
                ->first();

                if ($certificate != null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "already-exists")
                        ]
                    );
                }

                # set certificate to enterprise
                $certificate = new Certificate;
                $certificate->enterprise_id = (int) $enterprise->id;
                $certificate->password      = (string) $password;
                $certificate->name          = (string) $originalName;
                $certificate->pfx_file      = (string) $path . $temp;
                $certificate->crt_file      = (string) $path . $temp . ".crt";
                $certificate->valid_from    = (string) $validFrom;
                $certificate->valid_to      = (string) $validTo;
                $certificate->hash          = (string) $hash;
                $certificate->data          = (string) $certificateData;
                $certificate->save();

                if ($enterprise->id > 0) {

                    $check = explode('/', $data->telefone);

                    $phones = [];
                    if (count($check) > 1) {
                        foreach($check as $phone) {
                            $phone = $this->makePhoneNumber($phone);
                            if (is_array($phone) && count($phone) > 0) {
                                $phones[] = $phone;
                            }
                        }
                    } else {
                        $phone = $this->makePhoneNumber($data->telefone);
                        if (is_array($phone) && count($phone) > 0) {
                            $phones[] = $phone;
                        }
                    }

                    if (is_array($phones) && count($phones) > 0) {
                        foreach($phones as $phone) {
                            $check = EnterprisePhone::where('long_distance', $phone['long_distance'])
                            ->where('number', $phone['number'])
                            ->get();
                            if ($check->count() == 0) {
                                EnterprisePhone::create(
                                    [
                                        'international_code' => $phone['international_code'],
                                        'long_distance'      => $phone['long_distance'],
                                        'number'             => $phone['number'],
                                        'default'            => $phone['default'],
                                        'arm'                => null,
                                        'enterprise_id'      => $enterprise->id
                                    ]
                                );
                            }
                        }
                    }

                    if ($data->email != null) {

                        $emails = [];
                        $checkEmails = explode("/", $data->email);
                        if (is_array($checkEmails) && count($checkEmails) > 0) {
                            foreach($checkEmails as $email) {
                                if (strpos($email, "@")) {
                                    $emails[] = strtolower(trim($email));
                                }
                            }
                        }

                        if (count($emails) == 0) {
                            $checkEmails = explode(" ", $data->email);
                            if (is_array($checkEmails) && count($checkEmails) > 0) {
                                foreach($checkEmails as $email) {
                                    if (strpos($email, "@")) {
                                        $emails[] = strtolower(trim($email));
                                    }
                                }
                            }
                        }

                        if (is_array($emails) && count($emails) > 0) {
                            foreach($emails as $email) {
                                $checkEmail = EnterpriseEmail::check($email);
                                if ($checkEmail == true) {
                                    EnterpriseEmail::create(
                                        [
                                            'email'              => $email,
                                            'is_active'          => true,
                                            'enterprise_id'      => $enterprise->id
                                        ]
                                    );
                                }
                            }
                        } else {
                            $checkEmail = EnterpriseEmail::check($email);
                            if ($checkEmail == true) {
                                EnterpriseEmail::create(
                                    [
                                        'email'              => strtolower(trim($email)),
                                        'is_active'          => true,
                                        'enterprise_id'      => $enterprise->id
                                    ]
                                );
                            }
                        }
                    }

                    $check = UserEnterprise::where('user_id', $input['user_id'])
                    ->where('enterprise_id', $enterprise->id)
                    ->first();

                    if ($check == null) {
                        # set enterprise to user
                        $userEnterprise = new UserEnterprise;
                        $userEnterprise->enterprise_id = (int) $enterprise->id;
                        $userEnterprise->user_id = (int) $input['user_id'];
                        $userEnterprise->save();
                    }

                    # add new enterprise address
                    $check = EnterpriseAddress::where('enterprise_id', $enterprise->id)->first();

                    if ($check == null) {
                        $enterpriseAddress = EnterpriseAddress::create(
                            [
                                'enterprise_id' => (int)    $enterprise->id,
                                'number'        => (string) $data->numero,
                                'street'        => (string) strtolower($data->logradouro),
                                'complement'    => (string) strtolower($data->complemento),
                                'district'      => (string) strtolower($data->bairro),
                                'postal_code'   => preg_replace("/[^0-9\s]/", "", trim($data->cep)),
                                'city'          => (string) strtolower($data->municipio),
                                'state'         => (string) strtolower($data->uf),
                                'country_id'    => 1,
                                'is_active'     => true,
                                'default'       => true
                            ]
                        );
                        $enterpriseAddress = EnterpriseAddress::find($enterpriseAddress->id);
                    } else {
                        $enterpriseAddress = $check;
                    }

                    return response()->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, "created"),
                            'data' => [
                                'enterprise'  => $enterprise->getAttributes(),
                                'address'     => $enterpriseAddress->getAttributes(),
                                'certificate' => [
                                    'id'            => $certificate->id,
                                    'valid_from'    => $certificate->valid_from,
                                    'valid_to'      => $certificate->valid_to
                                ]
                            ]
                        ]
                    );

                } else {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message("enterprise", "not-found"),
                        ]
                    );
                }

            break;
        }
    }

    public function update(Request $request)
    {
        \App::setLocale('pt');

        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 18/04/2017
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'user_id'           => ['required', 'exists:user,id'],
                    'password'          => ['required'],
                    'national_code'     => ['required'],
                    'country_id'        => ['required']
                ];
                # define messages
                $messages = [];
                # validate input from request
                $validate = new \App\Services\Validator($input, $rules, $messages);

                if ($validate->status === false) {
                    return response()->json($validate);
                }

                if (!$request->hasFile('certificate') || !$request->file('certificate')->isValid()) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "not-found")
                        ]
                    );
                }

                if ( strtolower($input['country_id']) != 1) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "country-not-allowed")
                        ]
                    );
                }

                $file = $request->file('certificate');
                $originalName = $file->getClientOriginalName();
                $ext   = $file->getClientOriginalExtension();
                $temp  = date('Ymdhis');
                $name  = $temp . "." . $ext;
                $path  = public_path() . '/storage/app/certificate/';
                $file->move($path, $name);
                $password = $input['password'];

                # pfx
                if($ext == 'pfx'){
                    $cmd = "openssl pkcs12 -in {$path}{$name} -nokeys -nodes -out {$path}{$temp}.crt -password pass:{$password}";
                }               

                # cer
                if($ext == 'cer'){
                    $cmd = "openssl x509 -in {$path}{$name} -out {$path}{$temp}.crt";
                }   

                exec($cmd);

                # export to .crt
                $data = openssl_x509_parse(file_get_contents("{$path}{$temp}.crt"));

                if ($data == false) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "invalid-password")
                        ]
                    );
                }

                $hash      = $data['hash'];
                $certificateData = json_encode($data['subject']['OU']);
                $validFrom = date('Y-m-d H:i:s', $data['validFrom_time_t']);
                $validTo   = date('Y-m-d H:i:s', $data['validTo_time_t']);

                # get data
                $cn = explode(":", $data['subject']['CN']);
                $name = $cn[0];
                $cnpj = $cn[1];

                # enterprise correct?
                if($input['national_code'] != $cnpj) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'national-code-incorrect')
                        ]
                    );
                }

                # certificate valid?
                if($validTo < date('Y-m-d H:i:s')) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "certificate-expired")
                        ]
                    );
                } 

                # enterprise exist?            
                $enterprise = Enterprise::where("national_code", $cnpj)->first();

                if ($enterprise == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message('enterprise', 'not-found')
                        ]
                    );
                }

                # enterprise Address            
                #$enterpriseAddress = EnterpriseAddress::where('enterprise_id', $enterprise->id)->first();

                # certificate exist? 
                /*$certificate = Certificate::where('hash', $hash)
                ->where('enterprise_id', $enterprise->id)
                ->first();*/

                # certificate exist? 
                $certificate = Certificate::where('enterprise_id', $enterprise->id)
                ->first();

                if ($certificate == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, "not-found")
                        ]
                    );
                }                
                
                $data = array(
                    'password'      => $password,
                    'name'          => $originalName,
                    'pfx_file'      => $path . $temp,
                    'crt_file'      => $path . $temp . ".crt",
                    'valid_from'    => $validFrom,
                    'valid_to'      => $validTo,
                    'hash'          => $hash,
                    'data'          => $certificateData,
                );               

                $enterpriseCertificate = Certificate::where('id', $certificate->id)->update($data);

                return response()->json(
                        [
                            'status'  => true,
                            'message' => message(self::$controller, "certificate-updated"),
                            'data' => [
                                'password_old'  => $certificate->password,
                                'enterprise'    => $enterprise,
                                'certificate'   => [
                                    'id'            => $certificate->id,
                                    'valid_from'    => $validFrom,
                                    'valid_to'      => $validTo
                                ]
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
             * @author William Novak
             * @date 02/10/2016
             * @return void
             */
            case '1.0':
                $input = $request->input();

                # define rules
                $rules = [
                    'id'  => ['required'],
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

                $certificate  = Certificate::find($input['id']);

                if ($certificate == null) {
                    return response()->json(
                        [
                            'status'  => false,
                            'message' => message(self::$controller, 'not-found')
                        ]
                    );
                }

                $certificate->makeHidden(
                    [
                        'pfx_file', 'crt_file'
                    ]
                );

                $data = $certificate->getAttributes();
                $data['public'] = file_get_contents($data['crt_file']);

                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'found'),
                        'data'    => $data
                    ]
                );


            break;
        }
    }

    public function notify(Request $request)
    {
        switch ($request->version) {
            /**
             * @author Romualdo Bugai
             * @date 22/06/2017
             * @return void
             */
            case '1.0':

                $endDate   = date('Y-m-d', strtotime("+ 30 days", strtotime(date('Y-m-d'))));
                $startDate = date('Y-m-d');

                $certificates  = Certificate::whereBetween("valid_to", [$startDate, $endDate])->get();

                if ( $certificates->count() > 0 ) {
                    
                    foreach ($certificates as $k => $certificate) {
                        # info enterprise 
                        $enterprise = Enterprise::find($certificate['enterprise_id']);

                        if ($enterprise == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found'),
                                ]
                            );
                        }

                        # company relationship 
                        $userEnterprise  = UserEnterprise::where("enterprise_id", $certificate['enterprise_id'])->first();

                        if ($userEnterprise == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user-enterprise', 'not-found'),
                                ]
                            );
                        }

                        # info user
                        $user = User::find($userEnterprise->user_id);

                        if ($user == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user', 'not-found'),
                                ]
                            );
                        }

                        # send email
                        $title  = message('common', 'mail.certificate-notify.title');

                        $data = [
                            'user'          => $user,
                            'enterprise'    => $enterprise,
                            'certificate'   => $certificate,
                            'template'      => [
                                'title'     => $title,
                                'language'  => \App::getLocale(),
                                'footer'    => 'Equipe Log'
                            ]
                        ];

                        Mail::send('account.certificate-notify', $data, function($message) use (&$user, &$title){
                            $message->to($user->email, $user->name)->subject($title);
                        });

                        Mail::send('account.certificate-notify', $data, function($message) use (&$user, &$title){
                            $message->to('romualdo.bugai@gmail.com', $user->name)->subject($title);
                        });

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message(self::$controller, 'done-centificate-found')
                            ]
                        );

                    }

                }

                # no expired certificate
                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'done-centificate-not-found')
                    ]
                );

            break;
            /**
             * @author Romualdo Bugai
             * @date 22/06/2017
             * @return void
             */
            case '1.1':

                $certificates  = Certificate::where("valid_to", "<=", date('Y-m-d'))->get();

                if ( $certificates->count() > 0 ) {
                    
                    foreach ($certificates as $k => $certificate) {
                        # info enterprise 
                        $enterprise = Enterprise::find($certificate['enterprise_id']);

                        if ($enterprise == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('enterprise', 'not-found'),
                                ]
                            );
                        }

                        # company relationship 
                        $userEnterprise  = UserEnterprise::where("enterprise_id", $certificate['enterprise_id'])->first();

                        if ($userEnterprise == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user-enterprise', 'not-found'),
                                ]
                            );
                        }

                        # info user
                        $user = User::find($userEnterprise->user_id);

                        if ($user == null) {
                            return response()->json(
                                [
                                    'status'  => false,
                                    'message' => message('user', 'not-found'),
                                ]
                            );
                        }

                        # send email
                        $title  = message('common', 'mail.certificate-notify-expired.title');

                        $data = [
                            'user'          => $user,
                            'enterprise'    => $enterprise,
                            'certificate'   => $certificate,
                            'template'      => [
                                'title'     => $title,
                                'language'  => \App::getLocale(),
                                'footer'    => 'Equipe Log'
                            ]
                        ];

                        Mail::send('account.certificate-notify-expired', $data, function($message) use (&$user, &$title){
                            $message->to($user->email, $user->name)->subject($title);
                        });

                        Mail::send('account.certificate-notify-expired', $data, function($message) use (&$user, &$title){
                            $message->to('romualdo.bugai@gmail.com', $user->name)->subject($title);
                        });

                        return response()->json(
                            [
                                'status'  => true,
                                'message' => message(self::$controller, 'done-centificate-found')
                            ]
                        );

                    }

                }

                # no expired certificate
                return response()->json(
                    [
                        'status'  => true,
                        'message' => message(self::$controller, 'done-centificate-not-found')
                    ]
                );

            break;
        }
    }

}
