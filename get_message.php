<?php
function main()
{
	require ("model/message_list_class.php");
	require ("model/users_class.php");
	$html_insert = "";
	$db = connect_db();

	if (isset($_COOKIE["userid"]))
	{
		$org_userid = $_COOKIE["userid"];
		//check if the user really exits.
		$user_data = Users_Table::select_by_userid($org_userid);
		if (count($user_data) != 0)
		{
			$last_view_message = $user_data[0]->last_view_message;
			
			$messages = Message_List_Table::select_by_last_view_message($last_view_message);
			foreach($messages as $msg){
				$nickname = $msg->poster_nickname;
				$img_url = sprintf("https://secure.gravatar.com/avatar/%s?s=60&amp;d=wavatar&amp;r=G", $msg->poster_email_md5);
				$message_bg_color = $msg->message_bg_color;
				$message = $msg->message;
				$html_insert .= sprintf(MESSAGE_FRAME, 
					$img_url, 
					$nickname, 
					$message_bg_color, 
					$message );
				$last_view_message = $msg->message_index;
			}
			Users_Table::update_last_view_message($org_userid, $last_view_message);
		}
	}
	return $html_insert;
}

echo main();
?>