<?php

namespace Src\Utils;

class GeoStalker
{	
	/**
	 * This fetches as much information as we can about the current IP address from the ipinfo.io service.
	 * If we need to get the IP address of the current request, we can use $_SERVER['REMOTE_ADDR'] which will return the accessing IP.
	 * I don't think that $_SERVER['REMOTE_ADDR'] won't work over a local network nicely in regards to accuracy. 
	 */
	public static function findLocationOfIp($ip)
	{
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
		
		return [
			'ip'			=> $details->ip,
			'city'	  		=> $details->city,
			'country' 		=> $details->country,
			'province'		=> $details->region,
			'postal_code' 	=> $details->postal,
		];
	}
}