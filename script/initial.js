function load() {
	var top_menu_bar_element = document.getElementById("top_menu_bar");
	var main_div_element = document.getElementById("main_div");
	var bottom_div_element = document.getElementById("bottom_div");

	top_menu_bar_element.style.height = "40px";
	main_div_element.style.height = (document.body.clientHeight - top_menu_bar_element.clientHeight - bottom_div_element.clientHeight) + "px";

	var send_btn = document.getElementById("send_btn");
	send_btn.style.height = document.getElementById("input_div").clientHeight + "px";

	var input_text_area = document.getElementById("input_text_area");
	input_text_area.style.width = (bottom_div_element.clientWidth - send_btn.clientWidth - 55) + "px";
	input_text_area.focus();
}

function GetXmlHttpObject() {
	var xmlHttp = null;
	try {
		// Firefox, Opera 8.0+, Safari
		xmlHttp = new XMLHttpRequest();
	} catch (e) {
		// Internet Explorer
		try {
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}

function user_view_time_update() {
	xmlHttp = GetXmlHttpObject();
	var url = "on_exit.php";
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function message_update() {
	xmlHttp = GetXmlHttpObject();
	var url = "get_message.php?now=" + new Date().getTime();
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
			if (xmlHttp.responseText != "") {
				var move_to_bottom = false;
				var main_div = document.getElementById("main_div");
				if (main_div.scrollTop == (main_div.scrollHeight - main_div.clientHeight)) {
					move_to_bottom = true;
				}
				main_div.innerHTML += xmlHttp.responseText;
				if (move_to_bottom) {
					//如果用户正在浏览以前的消息，那么不可以滚动到底部
					$("#main_div").animate({
						scrollTop : main_div.scrollHeight
					}, 800);
				}
			}
		}
	};
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

//每10分钟更新一次在线状态
setInterval("user_view_time_update()", "600000");
//每10分钟更新一次消息
setInterval("message_update()", "5000");
