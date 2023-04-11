<?php

namespace App\Http\Controllers\Service;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PostmanController extends \App\Http\Controllers\Controller {


	protected static $controller = 'postman';

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-02-21
    */
    public function schedule(Request $request) {
        
        $input = [
            'count'     => false,
            'start_at'  => Carbon::now()->format('Y-m-d') . ' 00:01',
            'end_at'    => Carbon::now()->format('Y-m-d') . ' 23:59',
        ];

        $route      = 'schedulingServiceGet';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);

        $total      = 0;

        if ($result['status'] == true) {
            foreach ($result['data']['scheduling'] as $s => $scheduling) {
                
                $userSchedule = self::scheduleById($scheduling['id']);
                
                if (isArray($userSchedule)) {

                    foreach($userSchedule as $u => $userScheduling) {
                        
                        $data = [
                            'user'          => (object) $userScheduling['user'],
                            'scheduling'    => (object) $scheduling
                        ];

                        $userData = $userScheduling['user'];

                        Mail::send('app.services.postman.scheduling', $data, function($message) use (&$userData, &$scheduling) {
                            $message->to($userData['email'], $userData['name'])->subject( $scheduling['title'] );
                        });    

                        $total++;
                    }

                }
            }
        }

        return response()->json([
            'status'    => (bool)   ( $total > 0 ? true : false ),
            'message'   => (string) message('services', 'send', ['total' => $total])
        ]);
    }

    protected static function scheduleById($scheduling_id) {
        
        $input = [
            'scheduling_id' => $scheduling_id
        ];

        $route      = 'schedulingUserServiceGet';
        $version    = '1.1';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);
        if ($result['status'] == true) {
            return $result['data']['scheduling_user'];
        }
        return [];
    }


}
