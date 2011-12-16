<?php

require_once '../includes/config.php';
header('Content-Type: application/x-javascript');

$data = array();

$flot_enabled = (isset($_GET['flot']) ? $_GET['flot'] : false);

$state = "";
if(isset($_GET['state'])) {
	$state = $_GET['state'];
}

if(isset($_GET['sessions'])) {
		
	$aid = isset($_GET['aid']) ? safeString($_GET['aid']) : -1;
	
	$sessionIds = explode(" ", $_GET['sessions']);
	
	if($flot_enabled == false) {
	    // Get experiment Id for each session
    	foreach($sessionIds as $sid) {
    		$eid = getSessionExperimentId($sid);

    		$data[] = array('experimentId' => (($aid != -1) ? $aid : $eid), 
    						'sessionId' => $sid, 
    						'fields' => getFields($eid),
    						'meta' => array(getSession($sid)),
    						'data' => getData($eid, $sid));
    	}
	}
	else {
	    
	    $eid = getSessionExperimentId($sessionIds[0]);
	    
	    $data['id'] = $eid;
	    $data['meta'] = getExperiment($eid);
	    $data['sessions'] = array();
	    
	    foreach($sessionIds as $sid) {
	        
	        $x = array();
	        $x['id']            = $sid;
	        $x['meta']          = array(getSession($sid));
	        $x['data']          = getData($eid, $sid);
	        $x['fields']        = getFields($eid);
	        $x['visibility']    = true;
	        
	        $data['sessions'][] = $x;
	    }
	    
	}
}
else if(isset($_GET['vsessions'])) {
    
}


for( $i = 0; $i < count($data[0]['fields']); $i++ ) {
    if( $data[0]['fields'][$i]['type_id'] == 7 )
        $tf = $i;
}

if( isset( $tf ) ) {
    if( is_numeric($data[0]['data'][0][$tf]) ) {
        for( $x = 0; $x < count($data[0]['data']); $x++ ) {    
            $data[0]['data'][$x][$tf] = $data[0]['data'][$x][$tf] / 1000;
        }
    }
}


$data = json_encode($data);

?>
var DATA = <?php echo $data; ?>;
var STATE = <?php echo '"'.$state.'"'; ?>;
