<?php

namespace Src\Services;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\App;

abstract class Service {

	protected $container;

	protected $request;
	protected $response;
	protected $url_params;

	public function __construct(Container $container, Request $request, Response $response, $params = [])
	{
		$this->container = $container;

		$this->request = $request;
		$this->response = $response;
		$this->url_params = $params;
	}

	/**
	 * More PHP magic!
	 * This is called when you try call a variable on a class, when it doesn't exist.
	 * For example, if we call $this->view it won't be found in Service.php, so it'll check $this->container for the 'view' variable.
	 * This allows us to interact with the SlimPHP app container to gain access to the full power of slim inside of our application.
	 */
	public function __get($name)
	{
		if ($this->container->has($name)) {
			return $this->container->get($name);
		}
	}

	/**
	 * This is just a shorthand to render TWIG templates.
	 * For more information on TWIG, look at https://twig.symfony.com/
	 */
	public function render($template, $params = [])
	{
		$params = array_merge([
			'ENV_APP_NAME' => getenv('APP_NAME'),
			'USER_ID' => (isset($_SESSION['user_id'])? $_SESSION['user_id'] : null),
			'USER_NAME' => (isset($_SESSION['username'])? $_SESSION['username'] : null),
		], $params);

		return $this->view->render($this->response, $template, $params);
	}

	/**
	 * This is a small redirect wrapper that accepts the URL to redirect to,
	 * as well as an optional HTTP status code that defaults to 301.
	 */
	public function redirect($url, $status = 301)
	{
		return $this->response->withStatus($status)->withHeader('Location', $url);
	}

	/**
	 * This is a cleaner way of retrieving the input we need. It's fairly dynamic and can either be passed
	 * a string (for a single entry), an array (for multiple entries), or nothing (for all fo the entries)
	 *
	 * Part of this was adapted from Laravel's Illuminate/Support package,
	 * https://github.com/illuminate/support/blob/master/Arr.php#L365 
	 * instead of importing the entire package.
	 */
	public function input($fields = null)
	{
		$parsed_body = $this->request->getParsedBody();
		
		if (is_string($fields)) {
			return $parsed_body[$fields];
		} elseif (is_array($fields)) {
			return array_intersect_key($parsed_body, array_flip($fields));
		} else {
			return $parsed_body;
		}
	}

	/**
	 * This function is purely for debug purposes. 
	 */
	protected function debug($object)
	{
		echo '<pre>';
		print_r($object);
		die();
	}
}