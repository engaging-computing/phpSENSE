<?php

require_once '../includes/config.php';

if(isset($_GET['action'])) {
	
	switch($_GET['action']) {
		case "delete":
			deleteUser($_GET['id']);
			echo "worked!";
			break;
		
		case "reset":
			resetUserPassword($_GET['id']);
			echo "worked!";
			break;
		
		case "admin":
			makeUserAdmin($_GET['id']);
			echo "worked!";
			break;
	}
}

?>