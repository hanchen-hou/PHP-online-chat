<?php

require_once ("model/users_class.php");

function main()
{
	if (isset($_COOKIE["userid"]) && isset($_POST["nickname"]) && isset($_POST["message_bg_color"]) )
	{
		$org_userid = $_COOKIE["userid"];
		
		$user_data = Users_Table::select_by_userid($org_userid); // Varify userid
		if (count($user_data) != 0)
		{
			$new_data = array();
			
			if(sizeof($_POST["nickname"]) > 0){
				$new_data['nickname'] = $_POST["nickname"];
			}else{
				$new_data['nickname'] = $user_data[0]->nickname;
			}
			
			if(isset($_POST["email"]) && sizeof($_POST["email"]) > 0){
				$new_data['email'] = $_POST["email"];
				$new_data['email_md5'] = md5($_POST["email"]);
			}else{
				$new_data['email'] = $user_data[0]->email;
				$new_data['email_md5'] = $user_data[0]->email_md5;
			}
			echo "https://secure.gravatar.com/avatar/".$new_data['email_md5']."?s=70&d=wavatar&r=G";
				
			if(sizeof($_POST["message_bg_color"]) > 0){
				$new_data['message_bg_color'] = $_POST["message_bg_color"];
			}else{
				$new_data['message_bg_color'] = $user_data[0]->message_bg_color;
			}
		}
		Users_Table::update_by_userid($org_userid, $new_data);
	}
}

main();
?>