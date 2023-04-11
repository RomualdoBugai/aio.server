<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Useful\User as User;
use Carbon\Carbon;

class SocialController extends Controller
{

    public function index()
    {
        return view('app.social.index', [
            'title'     => message('social', 'title'),
            'breadcrumbs' => [
                [
                    'link'  => false,
                    'label' => message("social", "social.index"),
                    'url'   => null,
                ], [
                    'link'  => false,
                    'label' => message("social", "social.index.page"),
                    'url'   => null
                ]
            ]
        ]);
    }

}
