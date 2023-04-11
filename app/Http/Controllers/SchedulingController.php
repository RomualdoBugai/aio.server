<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;
use Carbon\Carbon;

class SchedulingController extends Controller {

	protected static $controller 	= 'scheduling';

	protected static $fillable      = [
		'controller_id',
		'controller',
		'title',
		'description',
		'start_at',
		'start_hour_at',
		'end_at',
		'end_hour_at',
		'is_public'
	];

	protected static $guarded       = ['id'];

	 /**
	  * get one
	  * @param   int 	$id
	  * @param   string $controller
	  * @param   bool 	$get [default: false]
	  * @access  protected
	  * @return  array
	  * @version 1.0 2017-02-15
	  */
	protected static function get($id, $controller, $get = false) {
		if ($id > 0) {
			if ($get == false) {
				$input = [
					'for'	=> $controller,
					'id'    => (int) $id,
				];
				$route   = 'schedulingServiceOne';
				$version = '1.0';
			} else {

				switch ($controller) {

					case 'enterprise':
						$input = [
							'enterprise_id'    => (int) $id,
						];
						$route   = 'schedulingEnterpriseServiceGet';
						$version = '1.0';
					break;
				}
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
	  * @version 1.0 2017-02-15
	  */
	protected static function form($id, $controller, $controller_id)
	{
		$scheduling = [];

		switch ($controller) {
			case 'enterprise':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) {
						$scheduling = $result['data']['enterprise_scheduling'];
					}
				}
			break;
		}

		$scheduling['controller'] 		= $controller;
		$scheduling['controller_id'] 	= $controller_id;

		return view('app.scheduling.form', [
			'form'  => Form::make(self::$fillable, self::$guarded, 'scheduling', $scheduling)
		])->render();
	}

	/**
	  * new
	  * @param   Request $request
	  * @method  post
	  * @access  public
	  * @return  string  json
	  * @version 1.0 2017-02-15
	  */
	public function insert(Request $request) {
		$input  = $request->input();
		$post   = $input[self::$controller];
		$input  = $post;

		$input['for'] 		= $input['controller'];
		$input['id'] 		= $input['controller_id'];

		$route   			= 'schedulingServiceCreate';
		$version 			= '1.0';

		$input['description'] 	= str_replace(array("\r\n", "\r", "\n"), "<br />", $input['description']);
		$input['description'] 	= ucfirst(trim($input['description']));
		$input['title'] 		= ucfirst(trim($input['title']));
		$input['is_public']		= true;

		$lastChar = substr($input['description'], -1);
		if ( $lastChar != '.' || $lastChar != '!' || $lastChar != ';' ) {
			$input['description'] .= ".";
		}

		$input['start_at'] 		= implode(" ", [Carbon::createFromFormat(inputDateFormat(), $input['start_at'])->toDateString(), $input['start_hour_at']]);
		$input['end_at'] 		= $input['start_at'];
		$input['coordinates'] 	= implode(",", [$input['latitude'], $input['longitude']]);

		$client = new \App\Services\Client();
		$result = $client->execute($input, $route, $version);

		if ($result['status'] == true) {
			switch ($input['controller']) {

				case 'enterprise':

					$route   			= 'schedulingEnterpriseServiceCreate';
					$version 			= '1.0';

					$scheduling_id 			= $result['data']['scheduling']['id'];

					$input['enterprise_id'] = $input['id'];
					$input['scheduling_id'] = $scheduling_id;

					$client = new \App\Services\Client();
					$result = $client->execute($input, $route, $version);

					$route   				= 'schedulingUserServiceCreate';
					$version 				= '1.0';

					$input['user_id'] 		= userId();
					$input['scheduling_id'] = $scheduling_id;

					$client = new \App\Services\Client();
					$result = $client->execute($input, $route, $version);


				break;
			}
		}

		return response()->json($result);
	}

	/**
	  * update
	  * @param   Request $request
	  * @method  post
	  * @access  public
	  * @return  string  json
	  * @version 1.0 2017-02-15
	  */
	public function update(Request $request) {
		$input  = $request->input();
		$post   = $input[self::$controller];
		$input  = $post;

		$input['for'] 	= $input['controller'];
		$route   		= 'schedulingServiceUpdate';
		$version 		= '1.0';

		$client = new \App\Services\Client();
		$result = $client->execute($input, $route, $version);
		return response()->json($result);
	}

	/**
	  * new
	  * @param   Request  	$request
	  * @param   string 	$controller (enterprise)
	  * @param   int 		$controller_id (enterprise@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2017-02-15
	  */
	public function new(Request $request, $controller, $controller_id) {
	  	return self::form(null, $controller, $controller_id);
	}

	/**
	  * edit
	  * @param   Request  	$request
	  * @param   int 		$id
	  * @param   string 	$controller (enterprise)
	  * @param   int 		$controller_id (enterprise@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2017-02-15
	  */
	public function edit(Request $request, $id, $controller, $controller_id) {
		return self::form($id, $controller, $controller_id);
	}

	/**
	  * index
	  * @param   Request  	$request
	  * @param   string 	$controller (enterprise)
	  * @param   int 		$controller_id (enterprise@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2017-02-15
	  */
	public function index(Request $request, $controller, $controller_id)
	{
		$schedulings 	= [];
		# get
		$data 		= self::get($controller_id, $controller, true);

		# check
		if ($data['status'] == true) {
			$schedulings = $data['data'][$controller . "_scheduling"];
		}

		$schedulings = array_reverse($schedulings);

		# return view
		return view('app.scheduling.index', [
			'status'		=> (bool) 	$data['status'],
			'schedulings'	=> $schedulings
		])->render();
	}

}
