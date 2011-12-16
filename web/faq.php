<?php

require_once 'includes/config.php';

$id = isset($_REQUEST['id']) ? safeString($_REQUEST['id']) : -1;

$data = array();
$single = false;

if($id == -1) {
	$data = getFaqArticles();
}
else {
	$data = getFaqArticleById($id);
	$single = true;
}

if($single) {
	$smarty->assign('title', 'FAQ: ' . $data['title']);
}
else {
	$smarty->assign('title', 'Frequently Asked Questions');
}

$smarty->assign('data', $data);
$smarty->assign('single', $single);
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('help.tpl'));
$smarty->display('skeleton.tpl');

?>