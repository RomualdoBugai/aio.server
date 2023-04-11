<?php

namespace App\Http\Controllers\Support;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class IssueController extends \App\Http\Controllers\Controller {

    /**
    * new
    * @param   Request  $request
    * @method  get
    * @access  public
    * @return  View
    * @version 1.0 2017-02-22
    */
    public function index(Request $request)
    {

        $input = [
            'limit'  => 50,
            'offset' => 0,
            'count' => true
        ];
        $route      = 'issueServiceGet';
        $version    = '1.1';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);

        dd($result);

    }

}
