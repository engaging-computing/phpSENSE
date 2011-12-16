<?php

require_once '../includes/config.php';

if(isset($_GET['action'])) {
	
	switch($_GET['action']) {
		case "addfeature":
			addFeaturedExperiment($_GET['id']);
			echo "worked!";
			break;
		
		case "removefeature":
			removeFeaturedExperiment($_GET['id']);
			echo "worked!";
			break;
		
		case "rate":
			rateExperiment($_GET['id'], $_GET['value']);
			echo "worked!";
			break;
		
		case "hide";
			$user  = $session->getUser();
			echo $user['administrator'];
			if($user['administrator']) {
				hideExperiment($_GET['id']);
			}
			echo "worked!";
			break;
		
		case "unhide":
			$user  = $session->getUser();
			echo $user['administrator'];
			if($user['administrator']) {
				unhideExperiment($_GET['id']);
			}
			echo "worked!";
			break;
	}
}

?>