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

function createSession($token, $eid, $name = "", $description = "", $street = "", $city = "", $country = "", $default_read = 1, $default_contribute = 0, $finalized = 0, $debug_data = "") {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];
	
	$data = $db->query("SELECT sessions.session_id, sessions.finalized FROM sessions, experimentSessionMap WHERE sessions.session_id = experimentSessionMap.session_id AND experimentSessionMap.experiment_id = {$eid}");
	$numOfSessions = $db->numOfRows;
	
	if($db->numOfRows == 1 && $data[0]['finalized'] == 0) {
		
		$cords = getLatAndLon($street, $city, $country);
		$lat = $cords[1];
		$lon = $cords[0];
		
		$db->query("UPDATE sessions SET sessions.owner_id = '{$uid}',
										sessions.name = '{$name}',
										sessions.description = '{$description}',
										sessions.street = '{$street}',
										sessions.city = '{$city}',
										sessions.country = '{$country}',
										sessions.timecreated = NOW(),
										sessions.finalized = 1,
										sessions.timemodified = NOW(),
										sessions.default_read = {$default_read},
										sessions.default_contribute = {$default_contribute},
										sessions.latitude = '{$lat}',
										sessions.longitude = '{$lon}',
										sessions.debug_data = '{$debug_data}'
										WHERE sessions.session_id = {$data[0]['session_id']}");
		
		if($db->numOfRows) {
			return $data[0]['session_id'];
		}
		
		return false;
	}
	else {
		
		if($numOfSessions == 0) {
			$finalized = 0;
		}
		else {
			$finalized = 1;
		}
		
		$cords = getLatAndLon($street, $city, $country);
		$lat = $cords[1];
		$lon = $cords[0];
		
		$db->query("INSERT INTO sessions (owner_id, name, description, finalized, street, city, country, timecreated, timemodified, default_read, default_contribute, latitude, longitude, debug_data) VALUES( {$uid}, '{$name}', '{$description}', {$finalized}, '{$street}', '{$city}', '{$country}', NOW(), NOW(), {$default_read}, {$default_contribute}, {$lat}, {$lon}, '{$debug_data}')");
		
		if($db->numOfRows) {
			$sid = $db->lastInsertId();
			$db->query("INSERT INTO experimentSessionMap (session_id, experiment_id) VALUES({$sid}, {$eid})");

			return $sid;
		}
	}
	
	return false;
}

function getSession($sid) {
	global $db;
	
	$output = $db->query("SELECT	sessions.session_id, 
									sessions.owner_id, 
									sessions.name, 
									sessions.description, 
									sessions.street, 
									sessions.city, 
									sessions.country, 
									sessions.latitude,
									sessions.longitude,
									sessions.timecreated, 
									sessions.timemodified, 
									sessions.finalized,
									users.firstname, 
									users.lastname 
									FROM users, sessions
									WHERE users.user_id = sessions.owner_id 
									AND sessions.session_id = {$sid}");

	
	if($db->numOfRows) {
		return $output[0];
	}
	
	return false;
}

function getSessionPictures($sid){
    global $db;

    return $db->query("SELECT pictures.provider_url,
                              pictures.description
                       FROM pictures 
                       WHERE pictures.session_id = {$sid}");

   return $output;
}


function updateSession($sid, $values) {
    global $db;
    
    $updates = "";
    
    foreach($values as $k => $v) {
        $updates .= "sessions.{$k} = '{$v}', ";
    }
    
    $updates = substr($updates, 0, (strlen($updates)-2));
    
    $sql = "UPDATE `sessions` SET {$updates} WHERE sessions.session_id = {$sid}";
    $query = $db->query($sql);
    
    if($db->numOfRows) {
        updateTimeModifiedForSession($sid);
        return true;
    }
    
    return false;
}

function addFieldToSession($token, $sid, $name, $type_id, $unit_id = 1) {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];
		
	$db->query("INSERT INTO fields (`session_id`, `name`, `type_id`, `unit_id`) VALUES({$sid}, '{$name}', {$type_id}, {$unit_id})");
	
	if($db->numOfRows) {
		return $db->lastInsertId();
	}
	
	return false;
}

function getSessionsForExperiment($eid) {
	global $db;
	
	$sql = "SELECT 	sessions.session_id, 
									sessions.owner_id, 
									sessions.name, 
									sessions.description, 
					 				experimentSessionMap.experiment_id,
									sessions.street, 
									sessions.city, 
									sessions.country,
									sessions.latitude,
									sessions.longitude, 
									sessions.timecreated, 
									sessions.timemodified,
									sessions.debug_data, 
									users.firstname, 
									users.lastname 
									FROM users, experimentSessionMap, sessions
									WHERE experimentSessionMap.experiment_id = {$eid}
									AND sessions.session_id = experimentSessionMap.session_id
									AND sessions.finalized = 1
									AND users.user_id = sessions.owner_id
									ORDER BY sessions.timecreated DESC";	
	$output = $db->query($sql);
										
	if($db->numOfRows) {
		return $output;
	}
	
	return false;	
}

function getSessionTitle($eid) {
	global $db;
	
	$sql = "SELECT 	sessions.name
					FROM experimentSessionMap, sessions
					WHERE experimentSessionMap.experiment_id = {$eid}
					AND sessions.session_id = experimentSessionMap.session_id
					AND sessions.finalized = 1
					ORDER BY sessions.timecreated DESC";	
	$output = $db->query($sql);
										
	if($db->numOfRows) {
		return $output;
	}
	
	return false;	
}

function getSessionsTitle($sids) {
	global $db;
		
	$sql = 'SELECT name FROM sessions WHERE session_id = ';
	
	foreach($sids as $index=>$sid) {
	    if( $index == 0 )
	        $sql .= $sid . ' ';
	    else
	        $sql .= ' OR session_id = ' . $sid;
	}
	
	$sql .= ' AND finalized = 1	ORDER BY session_id ASC';
						
	$output = $db->query($sql);
										
	if($db->numOfRows) {
		return $output;
	}
	
	return false;	
}

function getSessionOwner($sid) {
	global $db;
	
	$output = $db->query("SELECT sessions.owner_id FROM sessions WHERE sessions.session_id = {$sid} LIMIT 0,1");
	
	if($db->numOfRows) {
		return $output[0]['owner_id'];
	}
	
	return false;
}

function getSessionExperimentId($sid) {
	global $db;
	
	$output = $db->query("SELECT experimentSessionMap.experiment_id FROM experimentSessionMap WHERE experimentSessionMap.session_id = {$sid} LIMIT 0,1");
	
	if($db->numOfRows) {
		return $output[0]['experiment_id'];
	}
	
	return false;
}

function browseMySessions($uid) {
	global $db;
	
	$output = $db->query("SELECT 	sessions.session_id, 
									sessions.name,
									sessions.description, 
									sessions.latitude,
									sessions.longitude,
									sessions.timecreated, 
									sessions.timemodified,
									sessions.timemodified AS `timeobj`,
									sessions.owner_id,
									experiments.experiment_id,
									experiments.name AS `experiment_name`
									FROM sessions, experimentSessionMap, experiments 
									WHERE sessions.owner_id = {$uid} 
									AND experimentSessionMap.session_id = sessions.session_id
									AND experimentSessionMap.experiment_id = experiments.experiment_id
									AND experiments.hidden = 0
									GROUP BY sessions.session_id");

	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getNumberOfSessions() {
	global $db;
	
	$output = $db->query("SELECT COUNT(*) AS `count` FROM sessions");
	
	return $output[0]['count'];
}

function addSessionMapping($eid, $sid) {
    global $db;
    
    $sql = "INSERT INTO experimentSessionMap (`session_id`, `experiment_id`) VALUES('{$sid}', '{$eid}')";
    $result = $db->query($sql);
    
    if($db->numOfRows) {
        updateTimeModifiedForSession($sid);
        return true;
    }
    
    return false;
}

function updateTimeModifiedForSession($sid) {
    global $db;
    
    $sql = "UPDATE `sessions` SET sessions.timemodified = NOW() WHERE sessions.session_id = {$sid}";
    $db->query($sql);
    
    return true;
}

function putData($eid, $sid, $data) {
	global $db, $mdb;
	
    //pull meta from experiment
	$fields = getFields($eid);
	$field_names = array();
	$row_count = 0;
	
    //fill field_names[] from experiment meta data
	foreach($fields as $field) {
		$field_names[] = $field['field_name'];
	}
			
    //i think this is a nasty version of if( isset($data) )
	if(($count = count($data)) > 0) {
        //for each data point (datum)
	    foreach($data as $datum) {

            //associatiave array that holds data to be entered
    		$row = array();

    		for($i = 0; $i < count($field_names); $i++) {
    			$value = $datum[$i];

                //hackey mongo wierdness
    			if(is_numeric($value) ) {
    				$value = $value + 0;
    			}

                //fill row with values to enter into mongo
    			$row[str_replace(".", "", $field_names[$i])] = $value;
    		}

    		$row['experiment'] = (int) $eid;
    		$row['session'] = (int) $sid;

            //insert row of data into mongo
    		$mdb->insert("e{$eid}", $row);

    		$row_count++;	
    	}
	}

	//if successful
	if($row_count > 0) {
	    $dbname = MDB_DATABASE;
    	$filename = "mongodb://localhost/{$dbname}/{$eid}/session:{$sid}";

        //post meta data
    	$db->query("INSERT INTO data (`session_id`, `format`, `uri`) VALUES({$sid}, 'local_csv', '{$filename}')");
    	updateTimeModifiedForSession($sid);
	}
	
	return $row_count;
}

function getData($eid, $sid, $get_header = false, $strip_keys = true) {
    global $mdb;

    $excluded = array("session", "experiment");

    $fields = getFields($eid);
    $data = array();
    
    // Get the data from MongoDB
	$results = $mdb->find("e{$eid}", array("session" => (int)$sid));

	if(count($results) > 0) {
	    if($get_header) {
    	    $header = array();
    	    $headers = array_keys($results[0]);
    	    
    	    foreach($headers as $h) {
    	        if(!in_array($h, $excluded)) $header[] = $h;
    	    }
    	    
        	$data[] = $header;
    	}

        //print_r($results);

    	foreach($results as $i => $r) {
    	    foreach($fields as $f) {
    	        $data[$i][$f['field_name']] = $r[$f['field_name']];
    	    }
    	}
    	    	
    	$results = $data;
    	unset($data);

    	if($strip_keys) {
    	    // Package the data so it makes sense
        	foreach($results as $result) {
        		$row = array();

        		foreach($result as $k => $v) {
        			if(!in_array($k, $excluded)) $row[] = $v;
        		}

        		$data[] = $row;
        	}        	
    	}
    	else {
    	    foreach($results as $result) { 
    	        $row = array();
    	        
    	        foreach($result as $k => $v) {
        			if(!in_array($k, $excluded)) $row[$k] = $v;
        		}
        		
        		$data[] = $row;
        	}
    	}
	}


	return $data;
}

function getDataSince($eid, $sid, $since) {
    global $mdb;
    
	$since = strtotime($since);
	
    $results = $mdb->find("e{$eid}", array("session" => (int)$sid));
    $excluded = array("session", "experiment", "_id");
    $data = array();
    
	$time_fld = (isset($results[0]['Time']) ? 'Time' : 'time');

    // Package the data so it makes sense
	foreach($results as $result) {
		$row = array();
		
		foreach($result as $k => $v) {
			if(!in_array($k, $excluded)) $row[$k] = $v;
		}
		
		$data[] = $row;
	}
		
	$output = array();
	for($i = 0; $i < count($data); $i++) {
		$data[$i][$time_fld] = strtotime($data[$i][$time_fld]);
		if($data[$i][$time_fld] > $since) {
			$rowr = array();
			
			foreach($data[$i] as $k => $v) $rowr[] = $v;
			
			$output[] = $rowr;
		}
	}
	
	return $output;
}

function deleteSession($sid){
    global $db;
    $output = $db->query("DELETE FROM sessions where session_id={$sid}");
    $output = $db->query("DELETE FROM experimentSessionMap where session_id={$sid}");
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function hideSession($sid) {
	global $db;
	
	$output = $db->query("UPDATE sessions SET sessions.finalized = 0 WHERE sessions.session_id = {$sid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function unhideSession($sid) {
	global $db;
	
	$output = $db->query("UPDATE sessions SET sessions.finalized = 1 WHERE sessions.session_id = {$sid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

/*

function addData($eid, $sid, $data) { // This is here for legacy support
    return insertData($eid, $sid, $data);
}

function putData($eid, $sid, $data) {
	
	$output_data = array();

	// Double check and make sure comment lines are not getting through
	$data_lines = explode("\n", $data);
	$limit = (count($data_lines)-1);
	
	for($i = 1; $i < $limit; $i++) {
		if(strpos($data_lines[$i], "#") === false) {
			$output_data[] = explode(",", trim($data_lines[$i], "\r"));
		}
	}
	
	return insertData($eid, $sid, $output_data);
}

function getData($eid, $sid) {
	global $db, $mdb;
		
	$data_meta = $db->query("SELECT data.format, data.uri FROM data WHERE session_id = {$sid} LIMIT 0,1");
	if(count($data_meta) == 1) {
		$data_meta = $data_meta[0];
	}
	else {
		return false;
	}
	
	$excluded = array("session", "experiment", "_id");
	$data = array();
	
	// Does the URI point to MongoDB?
	if(strpos($data_meta['uri'], "mongodb") !== false) {
		
		// Get the data from MongoDB
		$results = $mdb->find("e{$eid}", array("session" => (int)$sid));
		
		// Package the data so it makes sense
		foreach($results as $result) {
			$row = array();
			
			foreach($result as $k => $v) {
				if(!in_array($k, $excluded)) $row[] = $v;
			}
			
			$data[] = $row;
		}
	}
	
	return $data;
}
*/

?>
