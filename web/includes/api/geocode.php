<?php

function getLatAndLon($street, $city, $country = "") {
	
	$addr = $street . ' ' . $city . ' ' . $country;
	$url = "http://maps.google.com/maps/geo?key=".GMAP_KEY."&sensor=false&oe=utf-8&output=xml&q=" . urlencode($addr);
	$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	$response = curl_exec($ch);
	
	$xml = simplexml_load_string($response);
	if($xml->Response->Status->code == '200') {
		$coords = $xml->Response->Placemark->Point->coordinates;
	}
	else {
		$coords = "200,200";
	}

	return explode(",", $coords);
}

?>