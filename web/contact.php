<?php

require_once 'includes/config.php';

$smarty->assign('title', 'Contact Us');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('contact.tpl'));
$smarty->display('skeleton.tpl');

?>