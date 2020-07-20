<?php

namespace Src\Utils;

use Src\DAO\UserDAO;

class Validator {
	private $fields;
	private $rules;
	private $messages;

	private function __construct($fields, $rules) {
		$this->fields = $fields;
		$this->rules = $rules;
		$this->messages = [];
	}

	public function getMessages() {
		return $this->messages;
	}

	public function addErrorMessage($key, $error) {
		if (!isset($this->messages[$key])) {
			$this->messages[$key] = [];
		}

		$this->messages[$key][] = $error;
	}

	public function run() {
		foreach ($this->rules as $key => $value) {
			$rules_to_check = explode('|', $value);

			$field = isset($this->fields[$key]) ? $this->fields[$key] : null;

			foreach ($rules_to_check as $current_rule) {
				switch ($current_rule) {
					case 'required':
						if (empty($field)) {
							$this->addErrorMessage($key, $key . ' is required.');
						}
						break;
					
					case 'string':
						if (!is_string($field)) {
							$this->addErrorMessage($key, $key . ' must be a string.');
						}
						break;

					case 'password':
						if (strlen($field) < 6){
				            $this->addErrorMessage($key, "Passwords must be of 6 characters or more");
				        }
				        if (!preg_match( '/[A-Z]/', $field)){
				            $this->addErrorMessage($key, "Passwords must contain at least one uppercase letter");
				        }
				        if (!preg_match( '/[a-z]/', $field)){
				            $this->addErrorMessage($key, "Passwords must contain at least one lowercase letter");
				        }
				        if (!preg_match( '/[0-9]/', $field)){
				            $this->addErrorMessage($key, "Passwords must contain at least one number");
				        }
						break;

					case 'integer':
						if (!is_int($field)) {
							$this->addErrorMessage($key, $key . ' must be an integer');
						}
						break;

					case 'confirm':
						if (isset($this->fields[$key . '_confirm'])) {
							if ($this->fields[$key] !== $this->fields[$key . '_confirm']) {
								$this->addErrorMessage($key, ucfirst($key) . ' are not the same.');
							}
						}
						break;
					case 'email':
						if (empty($field)) {
							$this->addErrorMessage($key, $key . ' is required.');
						}
						break;
					case 'first_name':
						if (empty($field)) {
							$this->addErrorMessage($key, $key . ' is required.');
						}
						break;
					case 'last_name':
						if (empty($field)) {
							$this->addErrorMessage($key, $key, 'is required.');
						}
						break;
					
					case 'username_unique':
						$bean = UserDAO::fetch([$field], 'username');
						if(isset($bean) && $bean !== null) {
							$this->addErrorMessage($key, $key . ' is not unique');
						}
				}
			}
		}
	}

	/**
	 * This is a hack of a validator that allows us to do some basic things such as checking if something is required, is an integer, an array, or similar. 
	 * The first parameter is the fields we want to check,  and the second parameter is an array of rules.
	 * 
	 * The rules array must have the field name as the key, and a rule as the value. Multiple rules may be chained via | for example: 
	 *
	 * 'name' => 'required|string', 
	 *
	 */
	public static function validate($fields, $rules) {
		$validator = new Validator($fields, $rules);
		$validator->run();

		$messages = $validator->getMessages();
		if (count($messages) == 0) {
			return true;
		} else {
			return $messages;
		}
	}

}