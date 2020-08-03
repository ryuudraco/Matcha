<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Validator;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Src\DAO\MessagesDAO;
use Src\DAO\UserDAO;

class MessagesService extends Service 
{
	//run node server.js to work
	/**
	 * Just render the messages page
	 */
	public function viewPage()
	{

		$usr = UserDAO::fetch([$_SESSION['user_id']], 'ID');
		$threads = MessagesDAO::getThreadsWithUserBeans($usr);

		$params = ['threads' => $threads];
		return $this->render('message.html', $params);
	}

	public function viewMessages() {
		if($this->request->getAttribute('id') !== null && !empty($this->request->getAttribute('id'))) {
			$thread = $this->request->getAttribute('id');
			$usr = UserDAO::fetch([$_SESSION['user_id']], 'ID');

			$threads = MessagesDAO::getThreadsWithUserBeans($usr);
			$messages = MessagesDAO::getMessagesForThread($usr, $thread);

			$params = ['msgs' => $messages, 'threads' => $threads];

			return $this->render('message.html', $params);
		}
	}

	//just a test
	public function test() {

		$fields = $this->input([
            'message'
        ]);

		$msg =  $fields['message'];

		$client = new Client(new Version2X('//127.0.0.1:1337'));

		$client->initialize();
		// send message to connected clients
		$client->emit('broadcast', ['type' => 'msg', 'message' => $msg]);
		$client->close();
	}
}
