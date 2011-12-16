<?php

require_once '../includes/config.php';

if(isset($_GET['action'])) {
	
	switch($_GET['action']) {
		case "delete":
			deleteSupportArticle($_GET['id']);
			echo "worked!";
			break;
		
		case "publish":
			publishSupportArticle($_GET['id']);
			echo "worked!";
			break;
	}
}

?>