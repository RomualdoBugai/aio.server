<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class EmailController extends Controller
{

	protected static $controller 	= 'email';

	protected static $fillable      = [
		'controller_id',
		'controller',
		'email'
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
				$route   = 'emailServiceOne';
				$version = '1.0';
			} else {
				$input = [
					'for'	=> $controller,
					'id'    => (int) $id,
				];
				$route   = 'emailServiceGet';
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
		$email = [];

		switch ($controller) {
			case 'enterprise':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) $email = $result['data']['enterprise_email'];
				}
			break;

			case 'person':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) $email = $result['data']['person_email'];
				}
			break;

		}

		$email['controller'] 		= $controller;
		$email['controller_id'] 	= $controller_id;

		return view('app.email.form', [
			'form'  => Form::make(self::$fillable, self::$guarded, 'email', $email)
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
		$route   		= 'emailServiceCreate';
		$version 		= '1.0';

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
		$route   		= 'emailServiceUpdate';
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
		$emails 	= [];
		# get
		$data 		= self::get($controller_id, $controller, true);

		# check
		if ($data['status'] == true) {
			$emails = $data['data'][$controller . "_email"];
		}

		# return view
		return view('app.email.index', [
			'status'	=> (bool) 	$data['status'],
			'emails'	=> $emails
		])->render();
	}

}
