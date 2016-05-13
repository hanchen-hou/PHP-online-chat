<?php
require_once ("model/users_class.php");
require_once ("model/message_list_class.php");

function main()
{
	
	if (isset($_COOKIE["userid"]))
	{
		$org_userid = $_COOKIE["userid"];
		$user_data = Users_Table::select_by_userid($org_userid);
		
		if (count($user_data) > 0 && isset($_POST["message"])) //This user really exists.
		{
			$message = $_POST["message"];
			if (mb_strlen($message, 'utf-8') > 0 && mb_strlen($message, 'utf-8') < MAX_WORDS_NUM)
			{
				$userid = $user_data[0]->userid;
				$nickname = $user_data[0]->nickname;
				$poster_email_md5 = $user_data[0]->email_md5;
				$message_bg_color = $user_data[0]->message_bg_color;
				
				$new_message = new Message_List_Class(NULL, NULL, $userid, $nickname, $poster_email_md5, $message_bg_color, $message);
				Message_List_Table::insert($new_message);
			}
		}
	}
}

main();
?>