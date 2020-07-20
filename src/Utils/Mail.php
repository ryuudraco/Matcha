<?php

namespace Src\Utils;

class Mail
{
	/**
	 * This is a neater wrapper around Swift_Mailer library https://swiftmailer.symfony.com/
	 * It uses the singleton pattern, and exposes a single function called `send()` which accepts 4 parameters
	 *
	 * $subject -> string 	-> subject of the email
	 * $from   	-> array 	-> array of ['email' => 'name'] of who it is from
	 * $to      -> array    -> array of email addresses to send to. Entries in array can either be a string, or an array similar to $from
	 * $body    -> string   -> body of the email to send
	 */

	private static $instance;
	private $transport;

	private $smtp_host;
	private $smtp_port;
	private $smtp_user;
	private $smtp_pass;

	private function __construct()
	{
		$this->smtp_host = getenv('SMTP_HOST');
		$this->smtp_port = getenv('SMTP_PORT');
		$this->smtp_user = getenv('SMTP_USER');
		$this->smtp_pass = getenv('SMTP_PASS');

		$this->connect();
	}

	private function connect() 
	{
		$this->transport = (new \Swift_SmtpTransport($this->smtp_host, $this->smtp_port))
			->setUsername($this->smtp_user)
			->setPassword($this->smtp_pass);
	}

	# SINGLETON PATTERN
	private static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Mail();
		}

		return self::$instance;
	}

	private function doSend($subject, $from, $to, $body)
	{
		$mailer = new \Swift_Mailer($this->transport);

		$message = (new \Swift_Message($subject))
			->setFrom($from)
			->setTo($to)
			->setBody($body);

		return $mailer->send($message);
	}

	public static function send($subject, $from, $to, $body)
	{
		self::getInstance()->doSend($subject, $from, $to, $body);
	}
}
