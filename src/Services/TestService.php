<?php

namespace Src\Services;

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Utils\Validator;

class TestService extends Service 
{
	public function handle() {
		return $this->render('test.html', ['app_name' => getenv('APP_NAME')]);
	}
}