<?php

require_once '../includes/config.php';

if(isset($_GET['action'])) {
	
	switch($_GET['action']) {
		case "delete":
			deleteArticle($_GET['id']);
			echo "worked!";
			break;
		
		case "publish":
			publishArticle($_GET['id']);
			echo "worked!";
			break;
	}
}

?>