<?php

require_once 'includes/config.php';

$smarty->assign('title', 'About');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('about.tpl'));
$smarty->display('skeleton.tpl');

/* Comment123 */
?>