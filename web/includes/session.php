<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 -->
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