<?php

require_once '../includes/config.php';

if(!isset($_GET['exp']))
	$presort = $mdb->find('e350');
else
	$presort = $mdb->find('e' . $_GET['exp']);

$keys = array_keys($presort[0]);


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

/*foreach( $tmp as $dp ) {
    if(!isset($sortArray[$dp[$dc]]))
        $sortArray[$dp[$dc]] = array();
    else
        array_push( $sortArray[$dp[$dc]], $dp );
}*/

//Dump unsorted data

unset($tmp);

//Tabularize data

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

$content = '';

foreach($sortArray as $index=>$ses) {
    if( isset($ses[0][$i_ses]) ) {         //&& $ses[0][$i_ses] > 2830 ) {
    $content .= '<table id="table_' . $index . '">'; //'<form name="' . $ses[0][$i_ses] . ' ><table>';
 	$content .= '<thead><tr><th>Ctrl</th>';
	foreach($keys as $key)
		$content .= '<th>' . $key . '</th>';
	$content .= '</tr></thead>';
    foreach($ses as $dp) {
        $content .= '<tr>';
        foreach($dp as $i => $d) {
            if( $i == $i_id )
                $content .= '<td><input type="button" name="add↓" value="+"/><input type="button" name="sub" value="-"/><input type="button" name="add↑" value="+"/></td><td name="' . $keys[$i] . '"><input type="hidden" value="' . $d . '" />Mongo_ID</td>';
            else if($i == $i_ses || $i == $i_exp)
                $content .= '<td name="' . $keys[$i] . '"><input type="hidden" value="' . $d . '" />' . $d . '</td>';
			else
                $content .= '<td name="' . $keys[$i] . '"><input type="text" value="' . $d . '" /></td>';
                
        }
        $content .= '</tr>';    
    }
    $content .= '</table>';//'<input type="submit" value="Save!" class="submit" /></form>';

    }
}

$content .= '<input type="button" value="Last!" id="last" /><input type="button" value="Next!" id="next" />';

$smarty->assign('head', '<link rel="stylesheet" type="text/css" href="/html/css/table.css" />' .
						'<link href="/html/css/table/fancyTable.css" rel="stylesheet" media="screen" />' .
						'<script src="/html/js/lib/jquery.fixedheadertable.js"></script>' .
						'<script type="text/javascript" src="/html/js/edit.js"></script>' );
						
$smarty->assign('title', 'Edit Page');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $content);
$smarty->display('skeleton.tpl');