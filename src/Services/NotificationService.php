<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\DAO\UserDAO;
use Src\DAO\HistoryUserDAO;
use Src\DAO\HistoryDAO;

class NotificationService extends Service 
{

	public function fetchNotifications() {

		$user = UserDAO::fetch([$_SESSION['user_id']], 'ID');
		$history = HistoryUserDAO::getUnseenHistory($user);

		//we only want id of person, name, action
		//just build a nice array for json encode for api
		$notifications = [];
		foreach($history as $index => $record) {
			$notifications[$index]['id'] = $record->getHistory()->getOrigin_id();
			$notifications[$index]['name'] = $record->getUser()->getUsername();
			$notifications[$index]['action'] = $record->getHistory()->getAction();
			HistoryDAO::updateHistory($user, $record->getUser(), $record->getHistory()->getAction());
		}

		// echo "<pre>";
		// print_r($notifications);
		// echo "</pre>";
		return json_encode($notifications);
	}
}
