<?php

require_once 'api/authentication.php';
require_once 'api/user.php';

Class Session {
	
	var $first_name;
	var $last_name;
	
	var $username;
	var $userid;
	var $token;
	var $type;
	
	function Session() {
		$this->username = 'Guest';
		$this->userid = -1;
		$this->token = 'Guest';
		$this->type = -1;
		$this->first_name = "Un";
		$this->last_name = "Known";
	}
	
	function start_rest_session($session_token) {
		global $db;
		
		if($session_token != "") {

			if(($uid = getUserIdFromSessionToken($session_token))) {
				$this->token = $session_token;
				$this->userid = $uid;

				$details = getUserDetails($this->userid);
				$this->username = $details['email'];
				$this->type = $details['administrator'];
			}
		}
	}
	
	function start() {
		global $db;
		
		if(isset($_COOKIE['isense_login'])) {

			$session_token = $_COOKIE['isense_login'];
			$uid = getUserIdFromSessionToken($session_token);
			
			if($uid) {
				$this->token = $session_token;
				$this->userid = $uid;

				$details = getUserDetails($this->userid);
				$this->username = $details['email'];
				$this->type = $details['administrator'];
				$this->first_name = $details['firstname'];
				$this->last_name = $details['lastname'];
			}
		}
	}
		
	function login($un = "", $pw = "", $remember = false) {
	    
		if($un != "" && $pw != "") {    
			$session_token = login($un, $pw);

			if($session_token) {

				$this->token = $session_token['session'];
				$this->userid = $session_token['uid'];

				$details = getUserDetails($this->userid);
				$this->username = $details['email'];
				$this->type = $details['administrator'];
				$this->first_name = $details['firstname'];
				$this->last_name = $details['lastname'];

				$now = time();
				$timeout = (60*60*24*14);
				$to = $now + $timeout;

				if($remember) {
					setcookie('isense_login', $this->token, $to, "/");
				} else {
					setcookie('isense_login', $this->token, 0, "/");
				}

				return true;
			}
		}
		
		return false;
	}
	
	function logout() {
		removeToken($this->userid);
		$this->username = 'Guest';
		$this->userid = -1;
		$this->token = 'Guest';
		$this->type = -1;
		$this->first_name = NULL;
		$this->last_name = NULL;
	}
	
	function getUsername() {
		return $this->username;
	}
	
	function generateSessionToken() {
		return array('uid' => $this->userid, 'session' => $this->token);
	}
	
	function getUser() {
		$guest = 0;
		if($this->userid == -1) {
			$guest = 1;
		}
		return array('email' => $this->username, 'guest' => $guest, 'administrator' => $this->type, 'user_id' => $this->userid, 'first_name' => $this->first_name, 'last_name' => $this->last_name);
	}
}

?>