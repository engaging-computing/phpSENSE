<?php

function publishToTwitter($msg) {
	
	if(TWITTER_PUB) {
		$url = "http://".TWITTER_USER.":".TWITTER_PASS."@twitter.com/statuses/update.xml?status=" . urlencode($msg);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 
		$response = curl_exec($ch);
	}
		
	return true;
}

function publishToDelicious($title, $url, $note, $tags = array()) {	
	if(DELICIOUS_PUB) {
		/* Setup Zend */
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass('Zend_Service_Delicious');

		$delicious = new Zend_Service_Delicious(DELICIOUS_USER, DELICIOUS_PASS);
		
		$delicious->createNewPost($title, $url)
								->setNotes($note)
								->setTags($tags)
								->save();
	}
}

?>