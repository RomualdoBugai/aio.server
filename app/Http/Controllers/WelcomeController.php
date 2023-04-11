<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;

class WelcomeController extends Controller
{

    public function index()
    {

        $serviceCache   = new \ServiceCache;
        $enterprise     = $serviceCache::initialize('enterprise');
        $enterprises    = $enterprise::count();

        $data = [
            'title' => message('template', 'welcome'),
            'breadcrumbs' => [
                [
                    'link'  => false,
                    'label' => message('welcome', "index"),
                    'url'   => null
                ]
            ],
            'indicators'    => (object) [
                'enterprise' => $enterprises
            ],
        ];
        return view('app.welcome.index', $data);
    }

}
