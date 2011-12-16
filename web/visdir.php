<?php

require_once 'includes/config.php';

$vid = -1;

if(isset($_GET['id'])) { $vid = (int) safeString($_GET['id']); }
if($vid != -1 ) {
	$vis = getVisById($vid);
	if(is_array($vis)) {
		$url = "vis.php";
		$url .= "?sessions=" . urlencode($vis['sessions']);
		$url .= "&state=" . urlencode($vis['url_params']);
		$url .= "&is_saved=true";
		$url .= "&vid={$vid}";
		
		if($vis['is_activity'] == 1) {
		    $url .= "&aid=" . $vis['experiment_id'];
		}
		
		header("Location: {$url}");
	}
}

$smarty->assign('user', $session->getUser());
$smarty->assign('title', 'Can Not Find Visualization');
$smarty->assign('content', $smarty->fetch('visdir.tpl'));
$smarty->display('skeleton.tpl');

?>