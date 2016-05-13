<?php

function main()
{
	define('ABSPATH', dirname(dirname(__FILE__)) );
	require_once(ABSPATH.'/model/users_class.php');
	require_once(ABSPATH.'/model/message_list_class.php');
	
	/* Modify lib/config.php file first */
	Users_Table::create();
	Message_List_Table::create();
}

main();

?>