<?php

namespace Src\Utils;

# little wrapper around cryptography stuff, incase i need to change anything in it later
class Crypt
{
	public static function hash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public static function check($password, $hash)
	{
		return password_verify($password, $hash);
	} 
}