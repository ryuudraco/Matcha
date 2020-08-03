<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Validator;
use Src\Utils\DB;
use Src\DAO\UserDAO;
use Src\DAO\BlocksDAO;

class BrowseService extends Service 
{
	/**
	 * Just render the browse page
	 */
	public function viewPage()
	{

		$origin = UserDAO::fetch([$_SESSION['user_id']], 'ID');
		//get blocks
		$blockedUsers = BlocksDAO::getAllBlockedUsersForOrigin($origin);

		$allNotBlockedUsers = "";

		foreach($blockedUsers as $blockedUser) {
			$allNotBlockedUsers .= $blockedUser->getTarget_id() . ",";
		}

		$allNotBlockedUsers = rtrim($allNotBlockedUsers, ',');

		//get all users (not blocked ones)
		if(strlen($allNotBlockedUsers > 0)) {
			$users = UserDAO::getAllWhere("id NOT IN(" . $allNotBlockedUsers . ")");
		} else {
			$users = UserDAO::getAll();
		}

		$params = [ 'users' => $users ];
		return $this->render('browse.html', $params);
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