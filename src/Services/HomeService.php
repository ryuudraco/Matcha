<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Validator;

class HomeService extends Service 
{
	/**
	 * Just render the home page
	 */
	public function viewPage()
	{
		return $this->render('home.html');
	}

	/**
	 * try login the user using the username and password.
	 * if fail, render the login page with the error
	 * if success, redirect to member list
	 */
	public function handlePost() {
		$fields = $this->request->getParsedBody();
		$validation_result = Validator::validate($fields, [
			'username' => 'required|string',
			'password' => 'required',
		]);

		if ($validation_result !== true) {
			print_r($validation_result);die();
			// return $this->render('login', ['validation_result' => $validation_result]);
		}

		# proceed with login
		echo '@todo - login the user and take them to their profile';
	}
}