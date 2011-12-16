<?php

require_once 'includes/config.php';

$params = array('eid', 'uid', 'name', 'description', 'sessions');
$values = array();

$title = "Create Activity";
$aid = -1;

$done = false;

foreach($params as $param) {
    if(isset($_REQUEST[$param])) {
        $values[$param] = safeString($_REQUEST[$param]);
    }
}

if(!isset($values['uid'])) {
    $values['uid'] = $session->userid;
}

if(isset($_POST['activity_create'])) {
    $aid = createActivity($values['eid'], $values['sessions'], $values['uid'], $values['name'], $values['description']);
    $title = 'Activity Created!';
    $done = true;
}


$smarty->assign('values', $values);
$smarty->assign('done', $done);
$smarty->assign('aid', $aid);

$smarty->assign('user', $session->getUser());
$smarty->assign('title', $title);
$smarty->assign('content', $smarty->fetch('create-activity.tpl'));
$smarty->display('skeleton.tpl');

?>