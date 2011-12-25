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

function createToken($uid) {
	global $db;
	
	//removeToken($uid);
	
	$session_key = uniqid();
	
	$db->query("INSERT INTO tokens ( user_id, session_key, updated ) VALUES ( {$uid}, '{$session_key}', NOW() )");

	if($db->numOfRows) {
		return array('uid' => $uid, 'session' => $session_key);
	}
	
	return false;
}

function removeToken($uid) {
	global $db;
		
	$db->query("DELETE FROM tokens WHERE tokens.user_id = {$uid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function getUserIdFromSessionToken($session_token) {
	global $db;

	$output = $db->query("SELECT tokens.user_id FROM tokens WHERE session_key = '{$session_token}'");

	if($db->numOfRows) {
		return $output[0]['user_id'];
	}
	
	return false;
}

function getUserPasswordHash($uid) {
	global $db;
		
	$output = $db->query("SELECT users.password FROM users WHERE users.user_id = {$uid}");
	
	if($db->numOfRows) {
		return strtolower($output[0]['password']);
	}
	
	return false;
}

function sendWelcomeEmail($email, $firstName, $lastname) {
	
	$to = $email;
	$subject = "Welcome to iSENSE";
	$msg = <<<END
Hi $firstName,

You've successfully created an iSENSE account. You may now create new experiments and contribute data to existing experiments.

To start using your iSENSE account simply go to http://isenseproject.org and login!

Thank You,
The iSENSE Team

END;
	$headers = 'From: iSENSE Team <isense-noreply@isense.cs.uml.edu>' . "\r\n" .
			'Reply-To: isense-noreply@isense.cs.uml.edu' . "\r\n" .
			'X-Mailer: Awesome Mail Over PHP / ' . phpversion();
	
	mail($to, $subject, $msg, $headers);
}

function register($email, $firstName, $lastName, $password, $street, $city, $country) {
	global $db;
	
	if(assertUserDoesNotExists($email)) {
		$auth = uniqid();
		$work = createUser($auth, true, $firstName, $lastName, $password, $email, $street, $city, $country, false);
		
		if($work) {
			sendWelcomeEmail($email, $firstName, $lastName);
			return $work;
		}
	}

	return false;
}

function login($email, $password) {
	global $db;

	if($uid = assertUserExists($email)) {

		if(assertUserConfirmed($uid)) {

			$existingHash = getUserPasswordHash($uid);
			$checkHash = md5($password);
			
			if($existingHash == $checkHash) {
				return createToken($uid);
			}
		}
	}
	
	return false;
}

function logout($token) {
	return removeToken($token);
}

?>