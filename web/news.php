<?php

require_once 'includes/config.php';

$data = array();
$error = false;
$title = 'Could Not Find Article';

$id = -1;
if(isset($_GET['id'])) {
	$id = (int) safeString($_GET['id']);
	$data = getArticle($id);
	$title = 'News: ' . ucwords($data[0]['title']);
}

if($id == -1 || $id == "") { $error = true; }

$smarty->assign('error', $error);
$smarty->assign('data', $data[0]);
$smarty->assign('user', $session->getUser());
$smarty->assign('title', $title);
$smarty->assign('content', $smarty->fetch('news.tpl'));
$smarty->display('skeleton.tpl');

?>