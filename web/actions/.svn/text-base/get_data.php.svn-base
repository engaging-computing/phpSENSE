<?php

require_once '../includes/config.php';
error_reporting(E_ALL);

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$output = array("data" => array());

if(isset($_GET['session'])) {
    
    $sid = $_GET['session'];
    
    $eid = getSessionExperimentId($sid);

    $output['fields'] = getFields($eid);
	
	$output['meta'] = getSession($sid);
	$output['meta']['experiment_id'] = $eid;
	
	$output['data'] = getData($eid, $sid, false, false);
}

echo json_encode($output);

?>