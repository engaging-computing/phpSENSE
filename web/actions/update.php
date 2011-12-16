<?php

require_once '../includes/config.php';

$results = $db->query("SELECT * FROM sessions WHERE latitude = 200 AND longitude = 200");
$total = $db->numOfRows;
$count = 0;

foreach($results as $result) {
	$cords = getLatAndLon($result['street'], $result['city']);
	
	$id = $result['session_id'];
	$lat = $cords[1];
	$lon = $cords[0];
	
	if($lat != 200 && $lon != 200) {
		$db->query("UPDATE sessions SET latitude = {$lat}, longitude = {$lon} WHERE session_id = {$id}");
		$count++;
	}
	
	sleep(2);
}

echo "Total Found: " . $total . "<br/>";
echo "Total Fixed: " . $count . "<br/>";

?>