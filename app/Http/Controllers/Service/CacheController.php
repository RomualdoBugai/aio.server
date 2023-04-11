<?php

namespace App\Http\Controllers\Service;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;

class CacheController extends \App\Http\Controllers\Controller {

    public function clear(Request $request)
	{

        \Artisan::call('app', ['action' => 'cache:clear']);

        return response()
        ->json([
            'status' => true,
            'message' => message('setup', 'cache-cleared')
        ]);
	}

}
