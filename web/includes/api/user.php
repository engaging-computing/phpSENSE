<?php
/* Copyright (c) 2011, iSENSE Project. All rights reserved.
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
 */

function assertUserCanBrowseExperiment($uid, $eid) {
	global $db;
		
	$result = $db->query("SELECT default_read = 1 OR owner_id = {$uid} OR ( experiment_user_permissions.user_id = {$uid} AND experiment_user_permissions.experiment_id = {$eid} AND experiment_user_permissions.read = 1 ) AS `read` FROM experiments LEFT JOIN experiment_user_permissions ON (experiment_user_permissions.experiment_id = {$eid}) WHERE experiments.experiment_id = {$eid}");

	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function assertUserCanJoinExperiement($uid, $eid) {
	global $db;
		
	$result = $db->query("SELECT default_join = 1 OR owner_id = {$uid} OR ( ( experiment_user_permissions.user_id = {$uid} AND experiment_user_permissions.experiment_id = {$eid} AND experiment_user_permissions.join = 1 ) IS NOT NULL ) AS `join` FROM experiments LEFT JOIN experiment_user_permissions ON (experiment_user_permissions.experiment_id = {$eid}) WHERE experiments.experiment_id = {$eid}");

	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function assertUserExists($email) {
	return getUserId($email);
}

function assertUserDoesNotExists($email) {
	return (getUserId($email) == TRUE) ? FALSE : TRUE;
}

function assertUserConfirmed($uid) {
	global $db;
	
	$result = $db->query("SELECT users.user_id FROM users WHERE users.user_id = {$uid} AND users.confirmed = TRUE");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function assertValidToken($token) {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];
	
	$result = $db->query("SELECT tokens.user_id FROM tokens WHERE user_id = {$uid} AND session_id = {$session}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function createUser($auth, $confirmed, $firstName, $lastName, $password, $email, $street, $city, $country, $private, $administrator = 0) {
	global $db;
	
	$cords = getLatAndLon($street, $city, $country);
	$lat = $cords[1];
	$lon = $cords[0];
	
	$db->query("INSERT INTO `users` (`auth`, `confirmed`, `firstname`, `lastname`, `password`, `email`, `street`, `city`, `country`, `latitude`, `longitude`, `firstaccess`, `administrator`, `private`) VALUES ('{$auth}', '{$confirmed}', '{$firstName}', '{$lastName}', md5('{$password}'), '{$email}', '{$street}', '{$city}', '{$country}', '{$lat}', '{$lon}', NOW(), '{$administrator}', '{$private}')");

	if($db->numOfRows) {
		return $db->lastInsertId();
	}
	
	return false;
}

function deleteUser($uid) {
	global $db;
	
	$output = $db->query("UPDATE users SET users.confirmed = 0 WHERE users.user_id = {$uid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function makeUserAdmin($uid) {
	global $db;
	
	$output = $db->query("UPDATE users SET users.administrator = 1 WHERE users.user_id = {$uid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function getUserId($email) {
	global $db;
	
	$output = $db->query("SELECT users.user_id FROM users WHERE users.email = '{$email}' LIMIT 0,1");
	
	if($db->numOfRows) {
		return $output[0]['user_id'];
	}
	
	return false;
}

function getPublicProfile($uid) {
    global $db;
        
    $result = $db->query("SELECT private FROM users WHERE user_id = {$uid} LIMIT 0,1");
    $private = $result[0]['private'];
        
    unset($result);
        
    $result = $db->query("SELECT    users.user_id, 
                                    users.firstname, 
                                    users.lastname, 
                                    users.street, 
                                    users.city, 
                                    users.country, 
                                    users.latitude,
                                    users.longitude,
                                    users.email, 
                                    users.firstaccess, 
                                    users.administrator 
                                    FROM users 
                                    WHERE users.user_id = {$uid} LIMIT 0,1");
                                    
    $output = $result[0];
    
    if($private) {
        $output['lastname'] = $output['lastname'][0];
    }
    
    if($db->numOfRows) {
        return $output;
    }
    
    return false;
}

/**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 150 [ 1 - 512 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
     function get_gravatar( $email, $s = 150, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

function getUserAvatar($uid) {
    
    global $db;
    
    $details = getUserDetails($uid);
    
    if ($details && $details['email'] != '') {
        return get_gravatar($details['email']);
    }
    else {
        //Default
        return '/html/img/user.jpg';
    }
}

function getUserDetails($uid) {
	global $db;
	                    
    $output = $db->query("SELECT    users.user_id, 
                                    users.firstname, 
                                    users.lastname, 
                                    users.street, 
                                    users.city, 
                                    users.country, 
                                    users.latitude,
                                    users.longitude,
                                    users.email, 
                                    users.firstaccess, 
                                    users.administrator 
                                    FROM users 
                                    WHERE users.user_id = {$uid} LIMIT 0,1");
                                        
    if($db->numOfRows) {
		return $output[0];
	}
	
	return false;
}

function updateUserProfile($uid, $firstname, $lastname, $email, $street, $city, $country) {
	global $db;
	
	$cords = getLatAndLon($street, $city, $country);
	$lat = $cords[1];
	$lon = $cords[0];
	
	$db->query("UPDATE users SET 	users.firstname = '{$firstname}',
									users.lastname = '{$lastname}',
									users.street = '{$street}',
									users.city = '{$city}',
									users.country = '{$country}',
									users.email = '{$email}',
									users.latitude = '{$lat}',
									users.longitude = '{$lon}'
									WHERE users.user_id = {$uid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function resetUserPassword($uid) {
	global $db;
	
	$i = 0;
	$length = 8;
	$password = "";
	$possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";  

	while ($i < $length) { 
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

		if (!strstr($password, $char)) { 
			$password .= $char;
			$i++;
		}
	}
	
	$output = $db->query("UPDATE users SET users.password = md5('{$password}')");
	
	if($db->numOfRows) {
		$details = getPublicProfile($uid);
		sendPasswordResetEmail($details['email'], $details['firstname'], $details['lastname'], $password);
		return true;
	}
	
	return false;
}

function sendPasswordResetEmail($email, $firstName, $lastname, $password) {
	
	$to = $email;
	$subject = "You iSENSE Password Has Been Reset";
	$msg = <<<END
Hi $firstName,

Your iSENSE password has been reset. Your password is now: $password

To change your password go to http://isenseproject.org and login. Then click 'My Stuff', then click the 'Edit Profile' link.  

After that simply click the 'Change Password' link and fill out the form as directed.

Thank You,
The iSENSE Team

END;
	$headers = 'From: iSENSE Team <isense-noreply@isense.cs.uml.edu>' . "\r\n" .
			'Reply-To: isense-noreply@isense.cs.uml.edu' . "\r\n" .
			'X-Mailer: Awesome Mail Over PHP / ' . phpversion();
	
	mail($to, $subject, $msg, $headers);
}

function getNumberOfUsers() {
	global $db;
	
	$output = $db->query("SELECT COUNT(*) AS `count` FROM users");
	
	return $output[0]['count'];
}

function countNumberOfContributedSessions($uid) {
	global $db; 
	
	$output = $db->query("SELECT COUNT(*) AS `count` FROM sessions WHERE sessions.owner_id = {$uid} AND sessions.finalized = 1");
	
	return $output[0]['count'];
}

function countNumberOfContributedExperiments($uid) {
	global $db; 
	
	$output = $db->query("SELECT COUNT(*) AS `count` FROM experiments WHERE experiments.owner_id = {$uid} AND experiments.hidden = 0");
	
	return $output[0]['count'];
}

?>
