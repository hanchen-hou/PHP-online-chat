<?php 

define('ABSPATH', dirname(dirname(__FILE__)) );
require_once (ABSPATH.'/lib/common.php');

class Users_Class{
	private $user_index;		// INT PRIMARY KEY AUTO_INCREMENT
	private $userid;		// TEXT
	private $nickname;		// TEXT
	private $email;			// TEXT
	private $email_md5;		// TEXT
	private $last_view_time;	// DATE
	private $last_view_message;	// INT
	private $message_bg_color;	// TEXT
	
	public function __get($var_name){ 
		return $this->$var_name; 
	}
	
	
	public function __construct($user_index, $userid, $nickname, $email='', $email_md5, $last_view_time, $last_view_message = 0, $message_bg_color){
	
		$this->user_index = $user_index;
		
		if(is_null($userid)) return NULL;
		$this->userid= $userid;
		
		if(is_null($nickname)) return NULL;
		$this->nickname= $nickname;
		
		$this->email= $email;
		
		if(is_null($email_md5)) return NULL;
		$this->email_md5= $email_md5;
		
		$this->last_view_time= $last_view_time;
		
		$this->last_view_message= $last_view_message;
		
		if(is_null($message_bg_color)) return NULL;
		$this->message_bg_color= $message_bg_color;
	}
	
	public function __destruct() {
		// 
   	}
}

class Users_Table{
	static function insert($data){
		//connect db
		$mysqli = connect_db();
		$query = "INSERT INTO users (userid,nickname,email,email_md5,last_view_time,last_view_message,message_bg_color) VALUES (?, ?, '', ?, CURDATE(), ?, ?)";
		if ($stmt = $mysqli->prepare($query)) {
			//check if need to insert multi-rows
			if(is_array($data)){
				$size = count($data);
				for($i = 0; $i < $size; $i++){
					// Bind parameters 
					$stmt->bind_param("sssis", 
						$data[$i]->userid,
						$data[$i]->nickname,
						$data[$i]->email_md5,
						$data[$i]->last_view_message,
						$data[$i]->message_bg_color);
					
					// Execute query
					$stmt->execute();
				}
			}
			else
			{
				// Bind parameters 
				$stmt->bind_param("sssis", 
					$data->userid,
					$data->nickname,
					$data->email_md5,
					$data->last_view_message,
					$data->message_bg_color);
				
				// Execute query
				$stmt->execute();
			}
			$stmt->close();
		}
		$mysqli->close();
	}
	
	static function select_by_userid($userid){
		$data = array();
		//connect db
		$mysqli = connect_db();	
		$query = "select * from users where userid = ?";
		if ($stmt = $mysqli->prepare($query)) {
			// Bind parameters 
			$stmt->bind_param("s", $userid);
			// Execute query
			$stmt->execute();
			
			// Bind results
			$stmt->bind_result($user_index, $userid, $nickname, $email, $email_md5, $last_view_time, $last_view_message, $message_bg_color);
			for($i = 0; $stmt->fetch(); $i++) {
				$data[$i] = new Users_Class($user_index, $userid, $nickname, $email, $email_md5, $last_view_time, $last_view_message, $message_bg_color);
    			}
    			
			$stmt->close();
		}
		$mysqli->close();
		
		return $data;
	}
	
	static function update_by_userid($userid, $new_data){
		//connect db
		$mysqli = connect_db();	
		$query = "update users set nickname=?,email=?,email_md5=?,last_view_time=CURDATE(),message_bg_color=? where userid=?";
		if ($stmt = $mysqli->prepare($query)) {
			// Bind parameters 
			$stmt->bind_param("sssss",
				$new_data['nickname'], 
				$new_data['email'],
				$new_data['email_md5'],
				$new_data['message_bg_color'],
				$userid);
			// Execute query
			$stmt->execute();
			
			echo $stmt->error;
			$stmt->close();
		}
		$mysqli->close();
	}
	
	static function update_last_view_time($userid){
		$mysqli = connect_db();	
		$query = "update users set last_view_time=NOW() where userid=?";
		if ($stmt = $mysqli->prepare($query)) {
			// Bind parameters 
			$stmt->bind_param("s", $userid);
			// Execute query
			$stmt->execute();
			
			$stmt->close();
		}
		$mysqli->close();
	}
		
	static function update_last_view_message($userid, $last_view_message){
		$mysqli = connect_db();	
		$query = "update users set last_view_message=? where userid=?";
		if ($stmt = $mysqli->prepare($query)) {
			// Bind parameters 
			$stmt->bind_param("ss", $last_view_message, $userid);
			// Execute query
			$stmt->execute();
			
			$stmt->close();
		}
		$mysqli->close();
	}
	
	static function create(){
		$mysqli = connect_db();
		$sql = sprintf("create table %s.users (user_index INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
						userid TEXT NOT NULL,
						nickname TEXT NOT NULL,
						email TEXT NULL,
						email_md5 TEXT NOT NULL,
						last_view_time DATE NOT NULL,
						last_view_message INT(11) NOT NULL,
						message_bg_color TEXT NOT NULL)
						CHARACTER SET %s", DB_DATABASENAME, DB_CHARSET);
		$mysqli->query($sql);
		$mysqli->close();
	}	
}

?>