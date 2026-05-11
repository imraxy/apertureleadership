<?php

namespace App\Library\Config;

/**
* Protocol Class
*/
class Protocol
{
	
	/******** Get Home Page ********/
	public static function home()
	{
		if (!empty(getenv('HTTPS')) && getenv('HTTPS') != 'off') {
		    
			// Secure connection
			return secure_url('/');

		}else{

			// Insecure Connection
			return url('/');

		}
	}

}