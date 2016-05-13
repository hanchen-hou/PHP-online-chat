<?php
define("MAX_WORDS_NUM", 200);

define("MESSAGE_FRAME", "<div class=\"message\">
	<div class=\"info\">
		<img alt=\"\" src=\"%s\">
		<div>%s</div>
	</div>
	<div class=\"content\" style=\"background-color: %s;\">
		<p >%s</p>
	</div>
</div>");

define('ABSPATH', dirname(dirname(__FILE__)) );
require_once(ABSPATH.'/lib/config.php');

function connect_db()
{
	//connect database
	$db= new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASENAME);
	$db->query("set names 'utf8'");
	if (mysqli_connect_errno()) 
	{
		die('Database error');
		return NULL;
	}else{
		return $db;
	}
}


function create_random_string($str_length = 8)
{
	// random string dict
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	//$chars = "abcdefghijklmnopqrstuvwxyz0123456789";

	$str = "";
	for ($i = 0; $i < $str_length; $i++)
	{
		$str .= $chars[mt_rand(0, strlen($chars) - 1)];
	}

	return $str;
}

?>