<?php

require_once '../includes/config.php';
header('Content-Type: application/x-javascript');

$data = array();

if(isset($_GET['id'])) {
	$eid = (int) safeString($_GET['id']);
	$data = getSessionsForExperiment($eid);
}

$data = json_encode($data);

?>
var DATA = <?php echo $data; ?>;
var STATE = <?php echo '""'; ?>;