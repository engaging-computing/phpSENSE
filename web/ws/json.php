<?php
/* Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

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
