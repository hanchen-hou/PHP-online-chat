<?php

function new_user_initial($db)
{
	//each user must have unique userid
	//this do-while loop is inefficient!
	do
	{
		$new_userid = create_random_string(16);
		$user_data = Users_Table::select_by_userid($org_userid);
	}
	while(count($user_data) != 0);
	
	$new_nickname = create_random_string(8);
	$random_md5 = md5($new_userid);
	$message_bg_color = '#0067a6';
	$new_user = new Users_Class(NULL, $new_userid, $new_nickname, '', $random_md5, NULL, 0, $message_bg_color);
	Users_Table::insert($new_user);

	$GLOBALS["nickname"] = $new_nickname;
	$GLOBALS["email"] = "";
	$GLOBALS["email_md5"] = $random_md5;
	$GLOBALS["message_bg_color"] = $message_bg_color;

	// two days
	$expire = time() + 86400 * 2;
	setcookie("userid", $new_userid, $expire);
}

function main()
{
	require_once ("model/users_class.php");
	//connect database
	if (isset($_COOKIE["userid"]))
	{
		$org_userid = $_COOKIE["userid"];
		$user_data = Users_Table::select_by_userid($org_userid);
		
		// there should exist one and only one unique userid
		if (count($user_data) == 1)
		{
			Users_Table::update_last_view_time($org_userid);
			//two days
			$expire = time() + 86400 * 2;
			setcookie("userid", $org_userid, $expire);

			$GLOBALS["nickname"] = $user_data[0]->nickname;
			$GLOBALS["email"] = $user_data[0]->email;
			$GLOBALS["email_md5"] = $user_data[0]->email_md5;
			$GLOBALS["message_bg_color"] = $user_data[0]->message_bg_color;
		}
		else
		{
			new_user_initial($db);
		}
	}
	else
	{
		new_user_initial($db);
	}
}

main();
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Online Chat</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
		<link rel="stylesheet" type="text/css" href="css/main_style.css"/>
		<link rel="stylesheet" type="text/css" href="css/message_style.css"/>
		<script src="script/initial.js"></script>
		<script src="script/message.js"></script>
		<script src="script/menu.js"></script>
		<script src="script/jquery-2.1.4.min.js"></script>
	</head>
	<body onload="load()">
		<div id="top_menu_bar" >
			<img class="btn_img" src="img/top_menu_bar_btn.png" alt="" width="25" height="20" onclick="menu_list_show()"/>
			<div id="menu_bar_listbox" style="display: none">
				<div style="height: 40px;width:40px;background-color: #333" onclick="menu_list_hide()">
					<img class="btn_img" src="img/top_menu_bar_btn_active.png" alt="" width="25" height="20" onclick=""/>
				</div >
				<div class="inner" style="background-color: #333">
					<img id="avatar_img" class="avatar_img" src="https://secure.gravatar.com/avatar/<?php echo $GLOBALS["email_md5"]?>?s=70&d=wavatar&r=G" alt="avatar"/>
					<form name="input" method="post" action="">
						Nickname：
						<input type="text" name="nickname" placeholder="最多20个字符" maxlength="30" value="<?php echo $GLOBALS["nickname"]?>"/>
						<br/>
						Email：
						<input type="text" name="email" value="<?php echo $GLOBALS["email"]?>"/>
						<br/>
						Message Color：
						<input type="color" name="message_bg_color" value="<?php echo $GLOBALS["message_bg_color"]?>"/>
						<br/>
						<input type="button" value="完成" onclick="user_account_update()"/>
					</form>
				</div>
			</div>
			<h1>Online Chat</h1>
		</div>
		<div id="main_div" ></div>
		<div id="bottom_div">
			<div id="input_panel">
				<div id="input_div">
					<textarea id="input_text_area" rows="1" onkeyup="test_area_keydown(this)"></textarea>
					<div id="words_num_indicator" align="right" style="color:grey">
						0/200
					</div>
				</div>
				<div id="send_btn" onmousedown="this.style.background = '#00697d'" onmouseup="this.style.background = '#1f8784'" onclick="send_message()">
					<div>
						Post
					</div>
				</div>
				<div class="clear_div"></div>
			</div>
		</div>
	</body>
</html>