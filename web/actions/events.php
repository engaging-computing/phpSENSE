<?php

require_once '../includes/config.php';

if(isset($_GET['action'])) {
	
	switch($_GET['action']) {
		case "delete":
			deleteEvent($_GET['id']);
			echo "worked!";
			break;
	}
}

?>