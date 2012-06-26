<?php

require_once './includes/config.php';

if(isset($_REQUEST['exp'])) {
    if(isset($_REQUEST['exp']) && isset($_REQUEST['ses'])) {
        $eid = $_GET['exp'];
        $sid = $_GET['ses'];
    }
    
    $presort = getData($eid, $sid, false, false);
        
	//$presort = $mdb->find('e' . $_GET['exp']);
    $keys = array_keys($presort[0]);
    $me = $session->getUser();

    if($me['administrator']) {
        $tmp = getSessionsForExperiment($eid);
        foreach( $tmp as $ses_data ) {
            $owners[] = getSessionOwner($sid);
        }
        unset($tmp);
    }

    //Dump Keys into javascript

    $javascript = '<script> var keys = [';
    
    foreach( $keys as $i => $key ) {
        if( $i < (count($keys)-1) )
            $javascript .= '"' . $key . '", ';
        else
            $javascript .= '"' . $key . '"';
    }
    $javascript .= '];';
    
    $sortArray = $presort;
    
    //Dump Session Names into javascript
    
    $javascript .= ' var sessionNames = [';
    foreach( $session_name as $i => $sn ) {
        if( !+$i )
            $javascript .= '"' . $sn['name'] . '" ';
        else
            $javascript .= ', "' . $sn['name'] . '" ';
    }
    
    $javascript .= '];</script>';
    
    if(!sizeof($sortArray)) {
        $content = 'Error: You are not the creator of any of these sessions!';
    } else {


        $head = "";

        $head .= '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript"></script>';
        $head .= '<script type="text/javascript" src="/html/js/lib/jquery.jTable.js"></script>';
        
        $head .= '<link rel="stylesheet" type="text/css" href="/html/css/jTable.css"></link>';
        $head .= $javascript;
        $head .= '<script type="text/javascript" src="/html/js/edit.js"></script>';

        $smarty->assign('head', $head );
    }

    $smarty->assign('title', 'Experiment # <span id="experimentID"> ' . $_GET['exp'] . '</span> : ' . getNameFromEid($_GET['exp']) . '</div><div id="sessionID" style="display:none;"> ' . $_GET['ses'] );
    $smarty->assign('user', $session->getUser());

    $smarty->assign('i_ses', $i_ses);
    $smarty->assign('i_exp', $i_exp);
    $smarty->assign('tableKeys', $keys);
    $smarty->assign('sortArray', $sortArray);
    $smarty->assign('content', $smarty->fetch('edit.tpl'));
    $smarty->display('skeleton.tpl');
    
}