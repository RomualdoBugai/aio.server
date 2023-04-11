<?php

namespace App\Http\Controllers\Support;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;
use App\Services\Useful\User as User;

class IndexController extends \App\Http\Controllers\Controller {

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

        return view('app.support.index', [
            "title"         => message('support', 'index')
        ]);
    }

    /**
      * show
      * @param   Request  $request
      * @method  get
      * @access  public
      * @return  View
      * @version 1.0 2017-03-29
      */
    public function show(Request $request, $id)
    {
    
    }

}
