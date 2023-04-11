<?php

namespace App\Http\Controllers\Widget;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Useful\User as User;

class ExpensesController extends \App\Http\Controllers\Controller {

    /**
    * index
    * @param   Request  $request
    * @access  public
    * @return  string 	View
    * @version 1.0 2017-03-01
    */
    public function index(Request $request) {

        $input = [
            'start_at'  => '2017-01-01',
            'end_at'    => \Carbon\Carbon::now()->format('Y-m-d'),
            'is_active' => 1,
            'is_closed' => 0
        ];
        $route      = 'expenseServiceGet';
        $version    = '1.1';
        $client     = new \App\Services\Client();
        $result     = $client->execute($input, $route, $version);
        
        return view("app.widget.expenses.index", [
            'status' 	=> (bool) $result['status'],
            'expenses'  => ( $result['status'] == true ? $result['data']['expense'] : [] )
        ])->render();
    }

}
