<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Validator;
use Src\Utils\DB;
use Src\Utils\Mail;
use Src\DAO\UserDAO;
use Src\DAO\LikesDAO;
use Src\DAO\BlocksDAO;
use Src\DAO\HistoryDAO;
use Src\DAO\HistoryUserDAO;
use Src\DAO\FameDAO;


class ProfileService extends Service 
{
	/**
	 * Just render the profile page
	 */
	public function viewPage()
	{
		if($this->request->getAttribute('username') !== null && !empty($this->request->getAttribute('username'))) {
			//we are actually viewing someone elses profile
			$user = UserDAO::fetch([$this->request->getAttribute('username')], 'username');
			$origin = UserDAO::fetch([$_SESSION['user_id']], 'ID');
			if($user->getId() != $_SESSION['user_id']) {
				HistoryDAO::insertHistoryAction($user, $origin, 'viewed');
			}
		} else {
			$user = UserDAO::fetch([$_SESSION['user_id']], 'ID');
		}
		$likes = LikesDAO::countAllUserReceivedLikes($user);
		$myLike = LikesDAO::getLike($user, UserDAO::fetch([$_SESSION['user_id']], 'ID'));
		$blocks = BlocksDAO::countAllUserReceivedBlocks($user);
		$myBlock = BlocksDAO::getBlock($user, UserDAO::fetch([$_SESSION['user_id']], 'ID'));

		//to display on a button whether profile was already liked or not
		$liked = false;
		if(empty($myLike)) {
			$liked = false;
		} else {
			$liked = true;
		}

		$blocked = false;
		if(empty($myBlock)) {
			$blocked = false;
		} else {
			$blocked = true;
		}
		
		$history = HistoryUserDAO::getHistory($user);
		$params = ['user' => $user, 'count' => $likes, 'liked' => $liked, 'history' => $history, 'countBlock' => $blocks, 'blocked' => $blocked ];
		return $this->render('profile.html', $params);
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

	public function giveALikeOrUnlike() {
		$target = UserDAO::fetch([$this->request->getAttribute('username')], 'username');
		$origin = UserDAO::fetch([$_SESSION['user_id']], 'ID');

		LikesDAO::likeUnlikeProfile($target, $origin);
		FameDAO::addFame($target);

		return $this->redirect('/profile/' . $target->getUsername());
	}

	public function giveABlockOrUnblock() {
		$target = UserDAO::fetch([$this->request->getAttribute('username')], 'username');
		$origin = UserDAO::fetch([$_SESSION['user_id']], 'ID');

		BlocksDAO::blockUnblockProfile($target, $origin);
		$blockCount = BlocksDAO::countAllUserReceivedBlocks($target);
		FameDAO::removeFame($target);

		if ($blockCount >= 5) {
			Mail::send(
				'Your profile has been reported',
				[
					'no-reply@dating-app.com' => 'Dating App - No Reply',
				],
				[
					$target->getEmail() => $target->getFirst_name() . ' ' . $target->getLast_name(),
				],
				'Your profile has received too many blocks and has been reported, it will be investigated for fraudulent use.'
			);
		}
		return $this->redirect('/profile/' . $target->getUsername());
	}
}