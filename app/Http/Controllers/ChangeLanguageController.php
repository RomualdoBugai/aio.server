<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;

class ChangeLanguageController extends Controller
{

	public function change(Request $request, $language, $url)
	{
		$segments = explode("+", $url);
		$segments[0] = $language;
		$url = implode("/", $segments);
		$url = url("/") . "/" . $url;
		return redirect($url);
	}

}
