<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class PhoneController extends Controller
{

	protected static $controller 	= 'phone';

	protected static $fillable      = [
		'controller_id',
		'controller',
		'international_code',
		'long_distance',
        'number',
        'arm'
	];

	protected static $guarded       = ['id'];

	 /**
	  * get one
	  * @param   int 	$id
	  * @param   string $controller
	  * @param   bool 	$get [default: false]
	  * @access  protected
	  * @return  array
	  * @version 1.0 2016-01-06
	  */
	protected static function get($id, $controller, $get = false)
	{
		if ($id > 0) {
			if ($get == false) {
				$input = [
					'for'	=> $controller,
					'id'    => (int) $id,
				];
				$route   = 'phoneServiceOne';
				$version = '1.0';
			} else {
				$input = [
					'for'	=> $controller,
					'id'    => (int) $id,
				];
				$route   = 'phoneServiceGet';
				$version = '1.0';
			}

			$client         = new \App\Services\Client();
			$result         = $client->execute($input, $route, $version);
			return $result;
		}
	}

	/**
	  * new
	  * @param   Request  $request
	  * @method  get
	  * @access  public
	  * @return  View
	  * @version 1.0 2016-01-06
	  */
	protected static function form($id, $controller, $controller_id)
	{
		$phone = [];

		switch ($controller)
		{
			case 'enterprise':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) $phone = $result['data']['enterprise_phone'];
				}
			break;

			case 'user':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) $phone = $result['data']['user_phone'];
				}
			break;

			case 'person':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) $phone = $result['data']['person_phone'];
				}
			break;

		}

		$phone['controller'] 		= $controller;
		$phone['controller_id'] 	= $controller_id;


		$route      = 'countryServiceGet';
		$version    = '1.0';
		$client     = new \App\Services\Client();
		$countries  = $client->execute([], $route, $version);
		$countries  = $countries['data']['country'];
		$country    = [];

		foreach($countries as $k => $c) {
			$country[$c['id'] . "+" . $c['international_code']] = message("country", $c['name']);
		}

		return view('app.phone.form', [
			'form'  => Form::make(self::$fillable, self::$guarded, 'phone', $phone),
			'options'   => [
				'international_code' => $country
			]
		])->render();
	}

	/**
	  * new
	  * @param   Request $request
	  * @method  post
	  * @access  public
	  * @return  string  json
	  * @version 1.0 2016-01-06
	  */
	public function insert(Request $request)
	{
		$input  = $request->input();
		$post   = $input[self::$controller];
		$input  = $post;

		$input['for'] 	= $input['controller'];
		$input['id'] 	= $input['controller_id'];
		$route   		= 'phoneServiceCreate';
		$version 		= '1.0';

		$internationalCode = $input['international_code'];
		list($country, $international_code) = explode("+", $internationalCode);
		$input['international_code'] = $international_code;

		$client = new \App\Services\Client();
		$result = $client->execute($input, $route, $version);

		return response()->json($result);
	}

	/**
	  * update
	  * @param   Request $request
	  * @method  post
	  * @access  public
	  * @return  string  json
	  * @version 1.0 2016-01-06
	  */
	public function update(Request $request)
	{
		$input  = $request->input();
		$post   = $input[self::$controller];
		$input  = $post;

		$input['for'] 	= $input['controller'];
		$route   		= 'phoneServiceUpdate';
		$version 		= '1.0';

		$client = new \App\Services\Client();
		$result = $client->execute($input, $route, $version);
		return response()->json($result);
	}

	/**
	  * new
	  * @param   Request  	$request
	  * @param   string 	$controller (enterprise,user)
	  * @param   int 		$controller_id (enterprise@id,user@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2016-01-07
	  */
	public function new(Request $request, $controller, $controller_id)
	{
	  	return self::form(null, $controller, $controller_id);
	}

	/**
	  * edit
	  * @param   Request  	$request
	  * @param   int 		$id
	  * @param   string 	$controller (enterprise,user)
	  * @param   int 		$controller_id (enterprise@id,user@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2016-01-07
	  */
	public function edit(Request $request, $id, $controller, $controller_id)
	{
		return self::form($id, $controller, $controller_id);
	}

	/**
	  * index
	  * @param   Request  	$request
	  * @param   string 	$controller (enterprise,user)
	  * @param   int 		$controller_id (enterprise@id,user@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2016-01-07
	  */
	public function index(Request $request, $controller, $controller_id)
	{
		$phones 	= [];
		# get
		$data 		= self::get($controller_id, $controller, true);

		# check
		if ($data['status'] == true) {
			$phones = $data['data'][$controller . "_phone"];
		}

		# return view
		return view('app.phone.index', [
			'status'	=> (bool) 	$data['status'],
			'phones'	=> $phones
		])->render();
	}

}
