<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use App\Services\Form;

class AttachmentController extends Controller
{

	protected static $controller 	= 'attachment';

	protected static $fillable      = [
		'controller_id',
		'controller',
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
	  * @version 1.0 2017-02-06
	  */
	protected static function get($id, $controller, $get = false)
	{
		if ($id > 0) {

			if ($get == false) {
				$input = [
					'for'	=> $controller,
					'id'    => (int) $id,
				];
				$route   = 'attachmentServiceOne';
				$version = '1.0';
			} else {
				$input = [
					'for'	=> $controller,
					'id'    => (int) $id,
				];
				$route   = 'attachmentServiceGet';
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
	  * @version 1.0 2017-02-15
	  */
	protected static function form($id, $controller, $controller_id)
	{
		$follow_up = [];

		switch ($controller) {
			case 'enterprise':
				if ($id > 0) {
					$result = self::get($id, $controller);
					if ($result['status'] == true) {
						$follow_up = $result['data']['enterprise_follow_up'];
					}
				}
			break;
		}

		$follow_up['controller'] 		= $controller;
		$follow_up['controller_id'] 	= $controller_id;

		return view('app.attachment.form', [
			'form'  => Form::make(self::$fillable, self::$guarded, 'follow_up', $follow_up)
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
	public function insert(Request $request)
	{
		$input  = $request->input();

		$post   = $input[self::$controller];
		$input  = $post;

		$input['for'] 		= $input['controller'];
		$input['id'] 		= $input['controller_id'];
		$input['user_id']	= userId();
		$route   			= 'attachmentServiceCreate';
		$version 			= '1.0';

		$input['description'] = str_replace(array("\r\n", "\r", "\n"), "<br />", $input['description']);
		$input['description'] = ucfirst(trim($input['description']));

		$lastChar = substr($input['description'], -1);
		if ( $lastChar != '.' || $lastChar != '!' || $lastChar != ';' ) {
			$input['description'] .= ".";
		}

		$total = count($_FILES['attachment']['name']['files']);
		$files = [];

		for($i = 0; $i < $total; $i++) {
			$input["file[{$i}]"] = [
				'name' 		=> "file[{$i}]",
				'contents' 	=> fopen($_FILES['attachment']['tmp_name']['files'][$i], 'r'),
				'filename' 	=> $_FILES['attachment']['name']['files'][$i]
			];
		}

		$client = new \App\Services\Client();
		$result = $client->execute($input, $route, $version, true);

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
	public function update(Request $request)
	{
		$input  = $request->input();
		$post   = $input[self::$controller];
		$input  = $post;

		$input['for'] 	= $input['controller'];
		$route   		= 'attachmentServiceUpdate';
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
	public function new(Request $request, $controller, $controller_id)
	{
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
	public function edit(Request $request, $id, $controller, $controller_id)
	{
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
		$attachments 	= [];
		# get
		$data 		= self::get($controller_id, $controller, true);

		# check
		if ($data['status'] == true) {
			$attachments = $data['data'][$controller . "_attachment"];
		}

		$attachments = array_reverse($attachments);

		# return view
		return view('app.attachment.index', [
			'status'		=> (bool)  $data['status'],
			'attachments'	=> (array) $attachments
		])->render();
	}

}
