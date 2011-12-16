<?php

require_once 'includes/config.php';

$smarty->assign('title', 'Downloads');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('downloads.tpl'));
$smarty->display('skeleton.tpl');

?>