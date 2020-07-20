<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Crypt;
use Src\Utils\DB;
use Src\Utils\Mail;
use Src\Utils\Validator;
use Src\Utils\RandomStringGenerator;
use Src\DAO\PasswordResetDAO;
use Src\DAO\UserDAO;
use Src\Beans\UserBean;

class ResetService extends Service 
{
	/**
	 * Just render the reset-password page
	 */
	public function viewRequestPage()
	{
		return $this->render('request-password.html');
	}

	public function viewResetPage() {
		return $this->render('reset-password.html');
	}

	public function sendPassReset() {
		//this to check whether they ask for a password reset while logged in
		if(isset($_SESSION['user_id'])) {
			$user = UserDAO::fetch([$_SESSION['user_id']], 'ID');
			//echo $user->getEmail();
			self::createResetToken($user);
			echo "Password Reset Email Sent!";
		} else {
			$this->render('request-password.html');
		}

		
	}

	private function createResetToken(UserBean $user) {
		//see if user already requested pass reset but haven't used that token yet
		$passReset = PasswordResetDAO::fetch([$user->getEmail()], 'email');
		if($passReset == null && empty($passReset)) {
			//let's generate a token and save it into a temp table
			$token = (new RandomStringGenerator())->generate(32);
			PasswordResetDAO::insert($token, $user->getEmail());
			self::sendEmail($user, $token);
		}
	}

	private function sendEmail(UserBean $user, $token) {
		Mail::send(
			'Password Reset',
			[
				'no-reply@dating-app.com' => 'Dating App - No Reply',
			],
			[
				$user->getEmail() => $user->getFirst_name() . ' ' . $user->getLast_name(),
			],
			'Please click this link to reset your password: http://' . $_SERVER['HTTP_HOST'] . '/reset-password/' . $token
		);
	}

	/**
	 * try reset user's password.
	 * if fail, render the reset-password page with the error
	 * if success, show login page
	 */
	public function handleRequestPost() {
		$fields = $this->input([
            'email'
		]);

		$validation_result = Validator::validate($fields, [
            'email' => 'required'
		]);

		if ($validation_result !== true) {
			return $this->render('request-password.html', ['validation_errors' => $validation_result]);
		}

		//does this email exist in the db? if so, generate a token (if it doesn't exist and send an email)
		$user = UserDAO::fetch([$fields['email']], 'email');
		//email exists in the db
		if(isset($user) && !empty($user)) {
			self::createResetToken($user);
		}

		//if it doesn't exist, let them know that email has been sent regardless
		//(notification that email has been sent regardless? to prevent people from trying to figure out which emails exist and hacking)
		echo "Password Reset Email Sent!";

	}

	public function handlePassChange() {
		$token = $this->request->getAttribute('token');

		$fields = $this->input([
			'password',
			'password_confirm'
		]);

		$validation_result = Validator::validate($fields, [
			'password' => 'required|password|confirm'
		]);

		if ($validation_result !== true) {
			return $this->render('reset-password.html', ['validation_errors' => $validation_result]);
		}

		//check if token exists in the db
		$passReset = PasswordResetDAO::fetch([$token], 'token');

		//if it does exist and it matches the token in the url
		$update_state = false;
		if(isset($passReset) && !empty($passReset) && $passReset->getToken() === $token) {
			$user = UserDAO::fetch([$passReset->getEmail()], 'email');
			//update user pass
			UserDAO::updatePassword($user, $fields['password']);
			//delete the token
			PasswordResetDAO::delete($token, $passReset->getEmail());
			
			$update_state = true;
		}

		return $this->render('login.html', ['update_success' => $update_state]);

	}
}
