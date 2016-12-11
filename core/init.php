<?php

define('SERVER_HOST', 		'127.0.0.1');
define('SERVER_USERNAME',	'root');
define('SERVER_PASSWORD',	'');
define('SERVER_DATABASE',	'iqlab');


$db = new mysqli(SERVER_HOST, SERVER_USERNAME, SERVER_PASSWORD, SERVER_DATABASE);

function is_request_ajax()
{
	return (bool)(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}