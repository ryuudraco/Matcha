<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Crypt;
use Src\Utils\DB;
use Src\Utils\GeoStalker;
use Src\Utils\Mail;
use Src\Utils\Validator;

class RegisterService extends Service 
{
	/**
	 * Just render the register page
	 */
	public function viewPage()
	{
		return $this->render('register.html');
	}

	/**
	 * try register the user with their details.
	 * if fail, render the registe page with the error
	 * if success, send mail with validate link and show success page
	 */
	public function handlePost() {
		$fields = $this->input([
			'username',
			'password',
			'password_confirm',
			'email',
			'first_name',
			'last_name'
		]);

		$validation_result = Validator::validate($fields, [
			'username' => 'required|string',
			'password' => 'required|password|confirm',
			'email' => 'required',
			'first_name' => 'required|string',
			'last_name' => 'required|string'
		]);

		if ($validation_result !== true) {
			$this->debug($validation_result);
			return $this->render('register.html', ['validation_errors' => $validation_result]);
		}

		# @todo - do a DB::select and see if the username or email already exists?

		$verify_token = md5($fields['username'] . time() . rand());

		$password = Crypt::hash($fields['password']);

		$ip_address = $_SERVER['REMOTE_ADDR'];
		$geo_info = GeoStalker::findLocationOfIp($ip_address);

		DB::execute('
			INSERT INTO users (
				username, 
				password, 
				email, 
				first_name, 
				last_name, 
				created_at, 
				updated_at, 
				verify_token, 
				ip_address,
				city,
				province,
				country,
				postal_code
			) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
			[
				$fields['username'],
				$password,
				$fields['email'],
				$fields['first_name'],
				$fields['last_name'],
				date('Y-m-d H:i:s'),
				date('Y-m-d H:i:s'),
				$verify_token,
				$ip_address,
				$geo_info['city'],
				$geo_info['province'],
				$geo_info['country'],
				$geo_info['postal_code'],
			]
		);

		Mail::send(
			'Verify your registration',
			[
				'no-reply@dating-app.com' => 'Dating App - No Reply',
			],
			[
				$fields['email'] => $fields['first_name'] . ' ' . $fields['last_name'],
			],
			'Please click this link to verify your registration: http://' . $_SERVER['HTTP_HOST'] . '/verify/' . $verify_token
		);

		return $this->render('register-success.html');
	}

	public function handleVerify() {
		$verification_token = $this->url_params['token'];

		DB::execute('UPDATE users SET verify_at = ? WHERE verify_token = ?', [date('Y-m-d H:i:s'), $verification_token]);

		return $this->redirect('/login');
	}
}
