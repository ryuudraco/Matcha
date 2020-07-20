<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Crypt;
use Src\Utils\DB;
use Src\Utils\Validator;

class LoginService extends Service 
{
	/**
	 * Just render the login page
	 */
	public function viewPage()
	{
		return $this->render('login.html');
	}

	/**
	 * try login the user using the username and password.
	 * if fail, render the login page with the error
	 * if success, redirect to member list
	 */
	public function handlePost() {
		$fields = $this->input([
			'username',
			'password',
		]);
		$validation_result = Validator::validate($fields, [
			'username' => 'required|string',
			'password' => 'required',
		]);

		if ($validation_result !== true) {
			return $this->render('login.html', ['validation_errors' => $validation_result]);
		}

		$user = DB::select('SELECT * FROM users WHERE username = ? LIMIT 1', [
			$fields['username']
		]);

		if (count($user) < 1) {
			return $this->render('login.html', ['validation_errors' => ['username' => 'Username or password is incorrect']]);
		}

		$user = reset($user);
		$valid_password = Crypt::check($fields['password'], $user['password']);

		if (!$valid_password) {
			return $this->render('login.html', ['validation_errors' => ['username' => 'Username or password is incorrect']]);
		}

		$_SESSION['user_id'] = $user['id'];
		$_SESSION['username'] = $user['username'];

		return $this->redirect('/browse');
	}

	public function logout()
	{
    	unset($_SESSION['id']);
		session_destroy();
		return $this->redirect('/login');
	}
}
