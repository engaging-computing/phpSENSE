<?php

require_once 'includes/config.php';

$errors = array();

$aid = -1;
$vis = array();
$is_saved = false;
$is_activity = false;
$activity_meta = array();
$link = "";

if(isset($_GET['sessions'])) {
	
	if(isset($_GET['aid'])) {
	    $aid = safeString($_GET['aid']);
	    $is_activity = true;
	    $activity_meta = getActivity($aid);
	}
	
	if(isset($_GET['is_saved'])) {
	    $is_saved = $_GET['is_saved'];
	}
	
	if(isset($_GET['vid'])) {
	    $vis = getVisById($_GET['vid']);
	}
	
	$state = "";
	if(isset($_GET['state'])) {
		$state = urlencode($_GET['state']);
	}
	$smarty->assign('state', $state);
	
	$smarty->assign('sessions', urlencode($_GET['sessions']));
	$sessions = explode(" ", $_GET['sessions']);
	
	// Is this an activity
	if($is_activity) {
	    $name = 'Activity: ' . $activity_meta['name'];
	    $name = array("name" => $name);
	}
	else if($is_saved) {
	    $name = getExperimentNameFromSession($sessions[0]);
		$link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a> > ' . $vis['name'];
	}
	else {
	    if(count($sessions) == 1) {

    		$session_data = getSession($sessions[0]);
    		$session_name = $session_data['name'];

    		$name = getExperimentNameFromSession($sessions[0]);
    		$link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a> > '. $session_name;
    	}
    	else {
    		$name = getExperimentNameFromSession($sessions[0]);
    		$link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a>';
    	}
	}
	
}

$smarty->assign('aid', $aid);
$smarty->assign('vis', $vis);
$smarty->assign('is_saved', $is_saved);
$smarty->assign('activity', $is_activity);
$smarty->assign('activity_meta', $activity_meta);

$smarty->assign('link', $link);
$smarty->assign('title', $name['name']);
$smarty->assign('errors', $errors);

$smarty->assign('user', $session->getUser());

if(FLOT_ENABLED == TRUE) {
    $smarty->assign('head', $smarty->fetch('parts/vis-head-flot.tpl'));
}
else {
    $smarty->assign('head', $smarty->fetch('parts/vis-head.tpl'));
}

$smarty->assign('content', $smarty->fetch('vis.tpl'));
$smarty->display('skeleton.tpl');

?>