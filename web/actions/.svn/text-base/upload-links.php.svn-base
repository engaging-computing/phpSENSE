<?php

if(isset($_GET['url'])) {
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $_GET['url']); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$file = curl_exec($ch);

	if(preg_match("/<title>(.+)<\/title>/i",$file,$m)) { 
		echo $m[1]. ',' . $_GET['div'];
	}
	else {
		echo 'No Title Found,' . $_GET['div'];
	}
}

?>