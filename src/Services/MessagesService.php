<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Validator;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

class MessagesService extends Service 
{
	//run node server.js to work
	/**
	 * Just render the messages page
	 */
	public function viewPage()
	{
		return $this->render('message.html');
	}

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
