<?php

function adminGetEvents() {
	global $db;
	
	$output = $db->query("SELECT events.*, users.firstname, users.lastname FROM events, users WHERE users.user_id = events.author_id ORDER BY events.start DESC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function adminGetNews() {
	global $db;
	
	$output = $db->query("SELECT news.*, users.firstname, users.lastname FROM news, users WHERE users.user_id = news.author_id ORDER BY news.pubDate DESC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function adminGetExperiments() {
	global $db;
	
	$output = $db->query("SELECT experiments.*, users.firstname, users.lastname FROM experiments, users WHERE experiments.owner_id = users.user_id ORDER BY experiments.timecreated DESC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function adminGetUsers() {
	global $db;
	
	$output = $db->query("SELECT * FROM users ORDER BY users.firstaccess DESC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function adminGetFaqs() {
	global $db;
	
	$output = $db->query("SELECT supportArticles.*, users.firstname, users.lastname FROM supportArticles, users WHERE supportArticles.faq = 1 AND supportArticles.author_id = users.user_id");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function adminGetHelpArticles() {
	global $db;
	
	$output = $db->query("SELECT supportArticles.*, users.firstname, users.lastname FROM supportArticles, users WHERE supportArticles.faq = 0 AND supportArticles.author_id = users.user_id");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function eventDelete($eid) {
	global $db;
	
	if( $db->query("DELETE FROM events WHERE event_id='$eid'") )
		return 0;

	return 1;

}

function newsDelete($nid) {
	global $db;

	if( $db->query("DELETE FROM news WHERE article_id='$nid'") )
	    return 0;

	return 1;

}

function newsPublish($nid) {
	global $db;

	if( $db->query("UPDATE news SET published='1' where article_id='$nid'") )
	    return 0;

	return 1;
}

function userDelete($uid) {
	global $db;

	if( $db->query("DELETE FROM users WHERE user_id='$uid'") )
	    return 0;

	return 1;

}

function userAdmin($uid) {
	global $db;

	if( $db->query("UPDATE users SET administrator='1' WHERE user_id='$uid'") )
	    return 0;

	return 1;

}

function experimentDelete( $eid ) {
	global $db;
	
	if( $db->query("DELETE FROM experiments WHERE experiment_id='$eid'") )
		return 0;

	return 1;

}

function experimentFeature( $eid ) {
	global $db;
	
	if( $db->query("UPDATE experiments SET featured='1' WHERE experiment_id='$eid'") )
		return 0;

	return 1;

}

function helpDelete( $hid ) {
	global $db;

	if( $db->query("DELETE FROM supportArticles where article_id='$hid'" ) )
		return 0;

	return 1;

}

function helpPublish($hid) {
	global $db;

	if( $db->query("UPDATE supportArticles SET published='1' where article_id='$hid'") )
	    return 0;

	return 1;
}

function faqDelete( $fid ) {
	global $db;

	if( $db->query("DELETE FROM supportArticles where article_id='$fid'" ) )
		return 0;

	return 1;

}

function faqPublish($fid) {
	global $db;

	if( $db->query("UPDATE supportArticles SET published='1' where article_id='$fid'") )
	    return 0;

	return 1;
}

function resetPass($uid) {
	
error_reporting(0);

	global $db;

	$headers = 'From: Admin@isense.cs.uml.edu';
	$subject = 'Password Retrieval';

	$result = $db->query("SELECT * FROM users WHERE user_id='$uid'");
	$email = $result[0]['email'];
	$lname = $result[0]['lastname'];

	$pass = generatePassword();

	$message = 'Hi, Mr/s $lname you were sent this email beacuse someone reset the password to your iSENSE account.\n\n Your new password is: $pass \n\n Thank you,\n The iSENSE team';

	$pass = md5($pass);

	$db->query("UPDATE users SET password='$pass' where user_id='$uid'");

	mail( $email, $subject, $message, $headers );  	

}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}


?>
