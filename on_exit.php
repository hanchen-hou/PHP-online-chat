<?php
require_once ("model/users_class.php");

function user_view_time_update()
{
	$org_userid = $_COOKIE["userid"];
	
	//update database
	Users_Table::update_last_view_time($org_userid );
	
	//update cookie
	//two days
	$expire = time() + 86400 * 2;
	setcookie("userid", $org_userid, $expire);
}

user_view_time_update();
?>