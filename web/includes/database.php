<?php

class Database {
	var $connection;
	var $ready;
	var $error;
	var $last;
	
	var $numOfRows;
	
	function Database($user, $pass, $server, $name) {
		$this->ready = false;
		$this->last = "";
		$this->connection = mysql_connect($server, $user, $pass, true);
		
		if($this->connection) {
			if(mysql_select_db($name, $this->connection)) {
				$this->ready = true;
			}
		}
	}
	
	function lastInsertId() {
		if($this->ready) {
			return mysql_insert_id($this->connection);
		}
		else {
			return false;
		}
	}
		
	function query($query) {
		$output = array();
		
		if($this->ready) {
			$this->last = $query;
			$result = mysql_query($query, $this->connection);
			
			$error = mysql_error();
			if($error != "") {
				echo $error;
				echo $query;
				$this->error = $error;
				$this->numOfRows = -1;
			}
			else {
				if(strpos($query, "INSERT") === FALSE && strpos($query, "UPDATE") === FALSE && strpos($query, "DELETE") === FALSE) {
					$output = array();
					
					$this->numOfRows = mysql_num_rows($result);
					while($row = mysql_fetch_assoc($result)) {
						$output[] = $row;
					}

					mysql_free_result($result);
					
					return $output;
				}
				else {
					$this->numOfRows = mysql_affected_rows($this->connection);
					return true;
				}				
			}
		}
		
		return false;
	}
	
	function disconnect() {
		if($this->ready) {
			mysql_close($this->connection);
		}
		
		return false;
	}
}

$db = new Database(DB_USER, DB_PASS, DB_HOST, DB_NAME);

?>