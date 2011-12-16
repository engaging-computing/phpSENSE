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