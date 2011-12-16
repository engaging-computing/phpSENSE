<?php

require_once '../includes/config.php';
error_reporting(E_ALL);
//echo "Hi?";

$errors = array();
$result = -1;
if(isset($_GET['action'])) {

	switch($_GET['action']) {
		case "save":

			$eid = -1;
			if(isset($_GET['eid'])) { $eid = (int) safeString($_GET['eid']); }
			if($eid == -1) { array_push($errors, 'You did not set the experiment id!'); }
			
			$name = "";
			if(isset($_GET['name'])) { $name = safeString($_GET['name']); }
			if($name == -1) { array_push($errors, 'You did not set the name!'); }

			$desc = "";
			if(isset($_GET['desc'])) { $desc = safeString($_GET['desc']); }
			if($desc == -1) { array_push($errors, 'You did not set the description!'); }

			$url_params = "";
			if(isset($_GET['url_params'])) { $url_params = safeString($_GET['url_params']); }
			if($url_params == "") { array_push($errors, 'You did not provide arguments for your visualization.'); }

			$sessions = "";
			if(isset($_GET['sessions'])) { $sessions = safeString($_GET['sessions']); }
			if($sessions == "") { array_push($erros, 'You did not provide any sessions'); }
			$sessions = split(",", $sessions);

			$uid = -1;
			if($session->userid > 0) { $uid = (int) $session->userid; }
			if($uid == -1) {  array_push($errors, 'You are not logged in!'); }
			
			// Check to see if experiment is an activity
			$is_activity = (isActivity($eid) ? 1 : 0);
			
			if(count($errors) == 0) {
				$result = createNewVis($uid, $eid, $name, $desc, $sessions, $url_params, $is_activity);
			}			
			break;
	}
	
	if(count($errors) > 0) {
		foreach($errors as $e) {
			echo  $e . '<br/>';
		}
	}
	else {
		echo $result;
	}
}

?>