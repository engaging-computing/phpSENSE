<?php

require_once 'includes/config.php';
require_once 'Flickr/phpFlickr.php';

$id = -1;
$done = false;
$ownerid = -1;
$errors = array();
$collabs = array();

if(isset($_GET['id'])) {
	$id = safeString($_GET['id']);
}
else if(isset($_POST['id'])) {
	$id = safeString($_POST['id']);
}

if($meta = getExperiment($id)) {
	$ownerid = $meta['owner_id'];
	$collabs = getExperimentCollaborators($session->userid, $id);
}

//Need to finish later...

$smarty->assign('errors', $errors);
$smarty->assign('done', $done);
$smarty->assign('title', ucwords($meta['name']) . ' - Add New Link');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('upload-links.tpl'));
$smarty->display('skeleton.tpl');

?>