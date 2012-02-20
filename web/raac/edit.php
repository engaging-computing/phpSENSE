<?php

require_once '../includes/config.php';

if(isset($_GET['exp'])) {
	$presort = $mdb->find('e' . $_GET['exp']);
	
    $keys = array_keys($presort[0]);

    $me = $session->getUser();

    if($me['administrator']) {
        $tmp = getSessionsForExperiment($_GET['exp']);
        foreach( $tmp as $ses_data ) {
            $owners[] = getSessionOwner($ses_data['session_id']);
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
    
    //Loads data from mongo into a sortable array
    foreach( $presort as $index => $row ) {
        for( $i = 0; $i < count($keys); $i++ ) {
			$tmp[$index][$i] = $row[$keys[$i]];
        }    
    }

    //Find session_id field and decrement it by 1

    $dc = count($tmp[0]);
    $dc--;

    //Create sortArray[Ses] for each session_id
    //Load each data point connected to a session into the appropriate session

    for( $t = 0; $t < count($tmp); $t++) {
	    if(!isset($sortArray[$tmp[$t][$dc]]))
		    $sortArray[$tmp[$t][$dc]] = array();

		array_push( $sortArray[$tmp[$t][$dc]], $tmp[$t] );
    }

    //Dump unsorted data

    unset($tmp);

    //Tabularize data
    //Set i_ses and i_exp
    $empty = 0;

    for( $in = 0; $in < count($keys); $in++ ) {
        if( $keys[$in] == 'Session' || $keys[$in] == 'session' )
            $i_ses = $in;
        else if( $keys[$in] == 'Experiment' || $keys[$in] == 'experiment' )
            $i_exp = $in;
        else if( $keys[$in] == '_id' )
            $i_id = $in;
        else
            $empty++;
    }

    if( $empty == count($keys) ) {
        $i_ses = $dc;
        $i_exp = $dc-1;
    }
    
    unset($empty);

    //Sort Data object by sessions with ses_id as the key
    $sessions = array_keys($sortArray);
    $newArray = $sortArray;

    sort($sessions);
    unset($sortArray);

    foreach( $sessions as $index=>$ses ) {
        $sortArray[$ses] = $newArray[$ses];
    }

    //Marks sessions for removal based on ownership
    foreach( $format_table as $index => $table ) {
        $rem[$table] = 0;
        if( intval($owners[$index]) != intval($me['user_id']) && !$me['administrator'] )
            $rem[$table]--;
    }
    
    //Unsets sessions marked for removal
    foreach( $format_table as $index => $table ) {
        if($rem[$table] == -1) {
            unset($sortArray[$table]);
            unset($session_name[$index]);
        } else
            $sortArray[$table] = $newArray[$table];
    }
    
    
    $session_name = getSessionsTitle(array_keys($sortArray));
    
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

    $smarty->assign('head', '<link rel="stylesheet" type="text/css" href="/html/css/table.css" />' .
					        '<link href="/html/css/table/fancyTable.css" rel="stylesheet" media="screen" />' .
					        '<script src="/html/js/lib/jquery.fixedheadertable.js"></script>' .
					        $javascript .
					        '<script type="text/javascript" src="/html/js/edit.js"></script>' );
                    }

    $smarty->assign('title', 'Experiment# : ' . getNameFromEid($_GET['exp']) . '</div><div id="sessionNumber">');
    $smarty->assign('user', $session->getUser());

    $smarty->assign('i_ses', $i_ses);
    $smarty->assign('i_exp', $i_exp);
    $smarty->assign('tableKeys', $keys);
    $smarty->assign('sortArray', $sortArray);
    $smarty->assign('content', $smarty->fetch('edit.tpl'));
    $smarty->display('skeleton.tpl');
    
}