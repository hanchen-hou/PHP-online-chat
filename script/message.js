var words_num = 0;
var max_words_num = 200;
var xmlHttp;

function send_message() {
	if (words_num > 0 && words_num < max_words_num) {
		var input_text_area = document.getElementById("input_text_area");
		var message = input_text_area.value;
		xmlHttp = GetXmlHttpObject();
		xmlHttp.open("POST", "send_message.php", true);
		xmlHttp.setRequestHeader("Content-Length", message.length);
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
		xmlHttp.send("message=" + message);

		input_text_area.value = "";
		words_num_check();
	}

}

function test_area_keydown(event) {
	if (event.ctrlKey && event.keyCode == 13) {
		send_message();
	}
	words_num_check();
}

function words_num_check() {
	words_num = document.getElementById("input_text_area").value.length;
	document.getElementById("words_num_indicator").innerHTML = words_num + "/" + max_words_num;
	if (words_num <= 200) {
		document.getElementById("words_num_indicator").style.color = "grey";
	} else {
		document.getElementById("words_num_indicator").style.color = "red";
	}
}
