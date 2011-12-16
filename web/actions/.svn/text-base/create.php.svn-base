<?php

require_once '../includes/config.php';

$term = $_REQUEST['q'];
$tags = getTags();

foreach ($tags as $tag) {
	if( strpos(strtolower($tag['tag']), $term) === 0 ) {
		echo $tag['tag']."\n";
	}
}

?>