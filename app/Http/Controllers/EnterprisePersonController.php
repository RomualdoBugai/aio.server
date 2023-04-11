<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class EnterprisePersonController extends Controller
{

	protected static $controller 	= 'enterprise_person';

	protected static $fillable      = [
		'enterprise_id',
		'name',
		'description'
	];

	protected static $guarded       = ['id'];

	/**
	  * get one
	  * @param   int 	$id
	  * @param   string $controller
	  * @param   bool 	$get [default: false]
	  * @access  protected
	  * @return  array
	  * @version 1.0 2017-02-13
	  */
	protected static function get($id, $get = false)
	{
		$version = '1.0';
		if ($id > 0)
		{
			if ($get == false) {
				$input 	= ['enterprise_person_id'    => (int) $id];
				$route  = 'enterprisePersonServiceOne';
			} else {
				$input 	= ['enterprise_id'    => (int) $id];
				$route  = 'enterprisePersonServiceGet';
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
	  * @version 1.0 2017-02-13
	  */
	protected static function form($id, $enterprise_id) {
		
		$enterprisePerson 	= ['enterprise_id' => $enterprise_id];
		$result 			= self::get($id);
		
		# check
		if ($result['status'] == true) {
			$enterprisePerson = $result['data'][self::$controller];
		}

		# return rendered view
		return view('app.enterprise.person.form', [
			'form'  => Form::make(self::$fillable, self::$guarded, self::$controller, $enterprisePerson)
		])->render();
	}

	/**
	  * new
	  * @param   Request $request
	  * @method  post
	  * @access  public
	  * @return  string  json
	  * @version 1.0 2017-02-13
	  */
	public function insert(Request $request)
	{
		$input  	= $request->input();
		$post   	= $input[self::$controller];
		$input  	= $post;

		$route  	= 'enterprisePersonServiceCreate';
		$version	= '1.0';

		$client 	= new \App\Services\Client();
		$result 	= $client->execute($input, $route, $version);

		return response()->json($result);
	}

	/**
	  * update
	  * @param   Request $request
	  * @method  post
	  * @access  public
	  * @return  string  json
	  * @version 1.0 2017-02-13
	  */
	public function update(Request $request)
	{
		$input  = $request->input();
		$post   = $input[self::$controller];
		$input  = $post;

		$route   		= 'enterprisePersonServiceUpdate';
		$version 		= '1.0';

		$client = new \App\Services\Client();
		$result = $client->execute($input, $route, $version);
		return response()->json($result);
	}

	/**
	  * new
	  * @param   Request  	$request
	  * @param   int 		$enterprise_id (enterprise@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2017-02-13
	  */
	public function new(Request $request, $enterprise_id)
	{
	  	return self::form(null, $enterprise_id);
	}

	/**
	  * edit
	  * @param   Request  	$request
	  * @param   int 		$id
	  * @param   int 		$enterprise_id (enterprise@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2017-02-13
	  */
	public function edit(Request $request, $id)
	{
		return self::form($id);
	}

	/**
	  * index
	  * @param   Request  	$request
	  * @param   int 		$enterprise_id (enterprise@id)
	  * @access  public
	  * @return  string 	View
	  * @version 1.0 2017-02-13
	  */
	public function index(Request $request, $enterprise_id)
	{
		$persons 	= [];
		# get all data from foreign key
		$data 		= self::get($enterprise_id, true);

		# check if status is true to fill the new variable
		if ($data['status'] == true)
		{
			$persons = $data['data'][self::$controller];
		}

		# return rendered view
		return view('app.enterprise.person.index', [
			'status'	=> (bool)	$data['status'],
			'persons'	=> (array) 	$persons
		])->render();
	}

}
