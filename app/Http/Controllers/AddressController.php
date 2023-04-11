<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class AddressController extends Controller
{

	protected static $controller 	= 'address';

	protected static $fillable      = [
		'controller_id',
		'controller',
		'street',
        'number',
        'district',
        'city',
        'state',
        'postal_code',
        'complement',
        'country_id'
	];

	protected static $guarded       = ['id', 'is_active', 'default'];

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
		if ($id > 0)
		{
			switch ($controller)
			{
				case 'enterprise':

					if ($get == false)
					{
						$input = [
							'enterprise_address_id'            => (int) $id,
						];
						$route   = 'enterpriseAddressServiceOne';
						$version = '1.0';
					} else {
						$input = [
							'enterprise_id'            => (int) $id,
						];
						$route   = 'enterpriseAddressServiceGet';
						$version = '1.0';
					}

				break;

				case 'user':

					if ($get == false)
					{
						$input = [
							'user_address_id'            => (int) $id,
						];
						$route   = 'userAddressServiceOne';
						$version = '1.0';
					} else {
						$input = [
							'user_id'            => (int) $id,
						];
						$route   = 'userAddressServiceGet';
						$version = '1.0';
					}

				break;
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
		$address = [];

		switch ($controller)
		{
			case 'enterprise':
				if ($id > 0)
				{
					$result = self::get($id, $controller);
					if ($result['status'] == true) $address = $result['data']['enterprise_address'];
				}
			break;

		}

		$route      = 'countryServiceGet';
		$version    = '1.0';
		$client     = new \App\Services\Client();
		$countries  = $client->execute([], $route, $version);
		$countries  = $countries['data']['country'];
		$country    = [];

		foreach($countries as $k => $c)
		{
			$country[$c['id']] = message("country", $c['name']);
		}

		$address['controller'] 		= $controller;
		$address['controller_id'] 	= $controller_id;

		return view('app.address.form', [
			'form'  => Form::make(self::$fillable, self::$guarded, 'address', $address),
			'options'   => [
				'country' => $country
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

		switch ($input['controller'])
		{
			case 'enterprise':
				$input['enterprise_id'] = $input['controller_id'];
				$route   = 'enterpriseAddressServiceCreate';
				$version = '1.1';
			break;

			case 'user':
				$input['user_id'] = $input['controller_id'];
				$route   = 'userAddressServiceCreate';
				$version = '1.0';
			break;
		}

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

		switch ($input['controller'])
		{
			case 'enterprise':
				$route   = 'enterpriseAddressServiceUpdate';
				$version = '1.0';
			break;
		}

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
		$addresses 	= [];
		# get
		$data 		= self::get($controller_id, $controller, true);
		# check
		if ($data['status'] == true)
		{
			$addresses = $data['data'][$controller . "_address"];
		}
		# return view
		return view('app.address.index', [
			'status'	=> (bool) 	$data['status'],
			'addresses'	=> $addresses
		])->render();
	}

}
