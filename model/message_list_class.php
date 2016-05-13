<?php

define('ABSPATH', dirname(dirname(__FILE__)) );
require_once (ABSPATH.'/lib/common.php');

class Message_List_Class{
	private $message_index;		// INT PRIMARY KEY AUTO_INCREMENT
	private $post_date;		// DATE #usually set by CURDATE()
	private $poster_userid;		// TEXT
	private $poster_nickname;	// TEXT
	private $poster_email_md5;	// TEXT
	private $message_bg_color;	// TEXT
	private $message;		// TEXT
		
	public function __get($var_name){ 
		return $this->$var_name; 
	}
	
	
	public function __construct($message_index, $post_date, $poster_userid, $poster_nickname, $poster_email_md5, $message_bg_color, $message){
		$this->message_index = $message_index;
		$this->post_date = $post_date;
		
		if(is_null($poster_userid)) return NULL;
		$this->poster_userid = $poster_userid;
		
		if(is_null($poster_nickname)) return NULL;
		$this->poster_nickname = $poster_nickname;
		
		if(is_null($poster_email_md5)) return NULL;
		$this->poster_email_md5 = $poster_email_md5;
		
		if(is_null($message_bg_color)) return NULL;
		$this->message_bg_color = $message_bg_color;
		
		if(is_null($message)) return NULL;
		$this->message = $message;
	}
	
	public function __destruct() {
		// 
   	}
}

class Message_List_Table{
	static function insert($data){
		//connect db
		$mysqli = connect_db();
		$query = "INSERT INTO message_list (post_date, poster_userid, poster_nickname, poster_email_md5, message_bg_color, message) values (CURDATE(), ?, ?, ?, ?, ?)";
		//$stmt =  $mysqli->stmt_init();
		if ($stmt = $mysqli->prepare($query)) {
			//check if need to insert multi-rows
			if(is_array($data)){
				$size = count($data);
				for($i = 0; $i<$size; $i++){
					// Bind parameters 
					$stmt->bind_param("sssss", $data[$i]->poster_userid, 
						$data[$i]->poster_nickname, 
						$data[$i]->poster_email_md5,
						$data[$i]->message_bg_color,
						$data[$i]->message);
					
					// Execute query
					$stmt->execute();
				}
			}
			else
			{
				// Bind parameters 
				$stmt->bind_param("sssss", $data->poster_userid, 
					$data->poster_nickname, 
					$data->poster_email_md5,
					$data->message_bg_color,
					$data->message);
				
				// Execute query
				$stmt->execute();
			}
			$stmt->close();
		}
		$mysqli->close();
	}
	
	
	static function select_by_last_view_message($index){
		$data = array();
		//connect db
		$mysqli = connect_db();	
		$query = "select * from message_list where post_date = CURDATE() and message_index > ?";
		if ($stmt = $mysqli->prepare($query)) {
			// Bind parameters 
			$stmt->bind_param("i", $index);
			// Execute query
			$stmt->execute();

			// Bind results
			$stmt->bind_result($message_index, $post_date, $poster_userid, $poster_nickname, $poster_email_md5, $message_bg_color, $message);
			for($i = 0; $stmt->fetch(); $i++) {
				$data[$i] = new Message_List_Class($message_index, $post_date, $poster_userid, $poster_nickname, $poster_email_md5, $message_bg_color, $message);
    			}
    			
			$stmt->close();
		}
		$mysqli->close();
		
		return $data;
	}
	
	static function create(){
		$mysqli = connect_db();
		$sql = sprintf("create table %s.message_list ( message_index INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
							post_date DATE NOT NULL,
							poster_userid TEXT NOT NULL,
							poster_nickname TEXT NOT NULL,
							poster_email_md5 TEXT NOT NULL,
							message_bg_color TEXT NOT NULL,
							message TEXT NOT NULL)
							CHARACTER SET %s",DB_DATABASENAME, DB_CHARSET);
		$mysqli->query($sql);
		$mysqli->close();
	}
}
?>