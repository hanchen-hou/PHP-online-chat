function menu_list_show() {
	var element = document.getElementById('menu_bar_listbox');
	element.style.display = 'block';
}

function menu_list_hide() {
	var element = document.getElementById('menu_bar_listbox');
	element.style.display = 'none';
}

function user_account_update() {
	var args = "";
	var new_nickname = document.input.nickname.value;
	if (new_nickname != "") {
		args += ("nickname=" + new_nickname + "&");
	}
	var new_email = document.input.email.value;
	if (new_email != "") {
		args += ("email=" + new_email + "&");
	}
	var new_message_bg_color = document.input.message_bg_color.value;
	if (new_message_bg_color != "") {
		args += ("message_bg_color=" + new_message_bg_color + "&");
	}

	xmlHttp = GetXmlHttpObject();
	xmlHttp.open("POST", "user_account_update.php", true);
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
			//update avatar
			if (xmlHttp.responseText) {
				document.getElementById("avatar_img").src = xmlHttp.responseText;
			}
		}
	};
	xmlHttp.setRequestHeader("Content-Length", args.length);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	xmlHttp.send(args);
}