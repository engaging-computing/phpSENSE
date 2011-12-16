<?php

require_once 'includes/config.php';

$errors = array();

/*
 *$smarty->assign('aid', $aid);
 *$smarty->assign('vis', $vis);
 *$smarty->assign('is_saved', $is_saved);
 *$smarty->assign('activity', $is_activity);
 *$smarty->assign('activity_meta', $activity_meta);
 *
 *$smarty->assign('link', $link);
 *$smarty->assign('title', $name['name']);
 *$smarty->assign('errors', $errors);
 *
 *$smarty->assign('user', $session->getUser());
 *
 *if(FLOT_ENABLED == TRUE) {
 *   $smarty->assign('head', $smarty->fetch('parts/vis-head-flot.tpl'));
 *}
 *else {
 *   $smarty->assign('head', $smarty->fetch('parts/vis-head.tpl'));
 *}
 */

// if newviz.php gets an arguement called sessions
if(isset($_REQUEST['sessions'])) {
    $sessions = explode(' ', $_REQUEST['sessions']);
    
    //Call data.php and return the JS Data object
    $head = '<script type="text/javascript" src="/ws/data.php?sessions=';
    
    //Append the session id for each session requested
    foreach($sessions as $ses)
        $head .= $ses . '+';
        
    //Strip the final '+'
    $head = substr($head, 0, -1);

    //Close the script
    $head .= '"></script>';
    
    $head .= '<script type="text/javascript" src="/html/js/modifiers.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/timeline.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/scatter.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/histogram.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/bar.js"></script>';
    $head .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/map.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/table.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/runtime.js"></script>';
    
    $smarty->assign('head', $head);
}
        

 
$smarty->assign('title', 'New Viz');
$smarty->assign('errors', $errors);

$smarty->assign('content', $smarty->fetch('newvis.tpl'));
$smarty->display('skeleton.tpl');

?>