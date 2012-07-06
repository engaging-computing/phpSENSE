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
 
function createExperiment($token, $name, $description, $fields, $req_name=1, $req_procedure=1, $req_location=1, $name_prefix=null, $location=null, $defaultJoin = true, $joinKey = "", $defaultBrowse = true, $browseKey = "") {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];
	
	if($name == "") {
		return false;
	}
	
	if($defaultJoin == false && $joinKey == "") {
		return false;
	}
	
	if($defaultBrowse == false && $browseKey == "") {
		return false;
	}
	
	$db->query("INSERT INTO experiments ( experiment_id, owner_id, name, description, timecreated, timemodified, default_read, default_join ,req_name, req_procedure, req_location, name_prefix, location) VALUES ( NULL, {$uid}, '{$name}', '{$description}', NOW(), NOW(), {$defaultBrowse}, {$defaultJoin}, '{$req_name}', '{$req_procedure}', '{$req_location}', '{$name_prefix}', '{$location}')");

	if($db->numOfRows) {
		
		$eid = $db->lastInsertId();
		
		$db->query("INSERT INTO experiment_user_permissions (`experiment_id`, `user_id`, `read`, `write`, `join`) VALUES({$eid}, {$uid}, 1, 1, 1)");
		
		publishToDelicious($name, 'http://isense.cs.uml.edu/rc1/experiment.php?id='.$eid, $description);
		
		return getExperiment($eid);
	}
	
	return false;
}

function getExperiment($eid) {
	global $db;
										
	$output = $db->query("SELECT experiments.*, 
                                 users.firstname, 
                                 users.lastname 
                                 FROM experiments, 
                                 users 
                                 WHERE experiments.owner_id = users.user_id AND experiments.experiment_id = {$eid}");
	
	if($db->numOfRows) {
		return $output[0];
	}
	
	return false;
}

function updateExperiment($eid, $values) {
    global $db;
    
    $updates = "";
    
    foreach($values as $k => $v) {
        $updates .= "experiments.{$k} = '{$v}', ";
    }
    
    $updates = substr($updates, 0, (strlen($updates)-2));
    
    $sql = "UPDATE `experiments` SET {$updates} WHERE experiments.experiment_id = {$eid}";
    $query = $db->query($sql);
    
    if($db->numOfRows) {
        updateTimeModifiedForExperiment($eid);
        return true;
    }
    
    return false;
}

function hideExperiment($eid) {
	global $db;
	
	$output = $db->query("UPDATE experiments SET experiments.hidden = 1 WHERE experiments.experiment_id = {$eid}");
	
	if($db->numOfRows) {
	    updateTimeModifiedForExperiment($eid);
		return true;
	}
	
	return false;
}

function unhideExperiment($eid) {
	global $db;
	
	$output = $db->query("UPDATE experiments SET experiments.hidden = 0 WHERE experiments.experiment_id = {$eid}");
	
	if($db->numOfRows) {
	    updateTimeModifiedForExperiment($eid);
		return true;
	}
	
	return false;
}

function addFeaturedExperiment($eid) {
	global $db;
	
	$db->query("UPDATE experiments SET featured = 1 WHERE experiment_id = {$eid}");
	
	$output = $db->query("SELECT name from experiments WHERE experiment_id = {$eid}");
	
	$name = "";
	if($db->numOfRows) {
		$name = $output[0]['name'];
	}
	
	if($name != "") {
		publishToTwitter('Latest Featured Experiment: "'.$name.'" - http://isense.cs.uml.edu/rc1/experiment.php?id='.$eid);
	}
	
	updateTimeModifiedForExperiment($eid);
	
	return true;
}

function removeFeaturedExperiment($eid) {
	global $db;
	
	$db->query("UPDATE experiments SET featured = 0 WHERE experiment_id = {$eid}");
	
	updateTimeModifiedForExperiment($eid);
	
	return true;
}

function rateExperiment($eid, $value) {
	global $db;
	
	$db->query("UPDATE experiments SET rating = rating + {$value}, rating_votes = rating_votes + 1 WHERE experiment_id = {$eid}");
		
	return true;
}

function countNumberOfSessions($eid) {
	global $db;
	
	$output = $db->query("SELECT COUNT(*) as `count` FROM experimentSessionMap, sessions WHERE sessions.finalized = 1 AND sessions.session_id = experimentSessionMap.session_id AND experimentSessionMap.experiment_id = {$eid} GROUP BY experimentSessionMap.experiment_id");
	
	if($db->numOfRows) {
		return $output[0]['count'];
	}
	
	return 0;
}

function countNumberOfContributors($eid) {
	global $db;
	
	$output = $db->query("SELECT COUNT(DISTINCT sessions.owner_id) as `count` FROM experimentSessionMap, sessions WHERE sessions.session_id = experimentSessionMap.session_id AND experimentSessionMap.experiment_id = {$eid}");
	
	if($db->numOfRows) {
		return $output[0]['count'];
	}
	
	return 0;
}

function getExperimentCollaborators($ownerid, $eid) {
	global $db;
		
	$output = $db->query("SELECT	users.user_id, 
									users.firstname, 
									users.lastname 
									FROM users, experimentSessionMap, sessions
									WHERE users.user_id = sessions.owner_id
									AND sessions.session_id = experimentSessionMap.session_id
									AND experimentSessionMap.experiment_id = {$eid} 
									AND users.confirmed = 1
									AND sessions.owner_id != {$ownerid} 
									GROUP BY users.user_id");
	
	if($db->numOfRows) {
		return $output;
	}

	return false;
}

function getExperimentNameFromSession($sid) {
	global $db;
	
	$output = $db->query("SELECT experiments.name, experiments.experiment_id FROM experimentSessionMap, experiments WHERE experimentSessionMap.session_id = {$sid} AND experiments.experiment_id = experimentSessionMap.experiment_id");
	
	if($db->numOfRows) {
		return $output[0];
	}
	
	return 'Visualization';
}

function getNameFromEid( $eid ) {
    global $db;
    
    $output = $db->query("SELECT name FROM experiments WHERE experiment_id = {$eid}");
    
	if($db->numOfRows) {
		return $output[0]['name'];
	}

	return 'Visualization';
}

function getExperimentsByTag($tag) {
	global $db;
	
	$sql = "SELECT  experiments.*, 
	                tagIndex.*,
	                users.firstname AS owner_firstname, 
					users.lastname AS owner_lastname
	                FROM tagIndex, tagExperimentMap, experiments
	                LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
	                WHERE tagIndex.value LIKE '%{$tag}%' 
	                AND tagIndex.tag_id = tagExperimentMap.tag_id 
	                AND experiments.experiment_id = tagExperimentMap.experiment_id 
	                AND tagIndex.weight = 1
                    ORDER BY experiments.timemodified DESC";           
	          

      
	$output = $db->query($sql);
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getExperimentsByName($tags){
	global $db;
	
    $sql = "SELECT DISTINCT experiments.* FROM experiments where experiments.name LIKE '{$tags}'";
    for($i=0; $i<count($tags);$i++){
            $sql .= "OR experiments.name LIKE '{$tags[$i]}%'";       
    }

    
    
   	$output = $db->query($sql);
  
	if($db->numOfRows) {
		return $output;
	}
	 
	return false;
          

}

function getFields($eid) {
	global $db;
		
	$output = $db->query("SELECT 	fields.field_id,   
									experimentSessionMap.experiment_id,
									fields.name AS field_name, 
									fields.type_id, 
									fields.unit_id, 
									types.name AS type_name, 
									units.name AS unit_name, 
									units.abbreviation AS unit_abbreviation 
									FROM fields, experimentSessionMap, types, units
									WHERE experimentSessionMap.experiment_id = {$eid} 
									AND fields.session_id = experimentSessionMap.session_id
									AND types.type_id = fields.type_id
									AND units.unit_id = fields.unit_id
									ORDER BY fields.field_id");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function experimentHasTime($eid) {
    
    $fields = getFields($eid);
    
    if($fields != NULL) {
        foreach($fields as $f) {

            // If the type if 7
            if($f['type_id'] == 7) {
                return $f['type_name'];
            }
        }
    }
    
    return false;
}

function packageBrowseExperimentResults($results, $page = 1, $limit = 10, $override = false) {
	
	$output = array();
	
	if($page != -1) {
		$offset = ($page - 1) * $limit;
		$results =  array_splice($results, $offset, $limit);

		if(!$override) {
			foreach($results as $result) {
			    $sessioncount = countNumberOfSessions($result['experiment_id']);
				$contribcount = countNumberOfContributors($result['experiment_id']);
			    
				$output[$result['experiment_id']] = array("meta" => $result, "tags" => array(), "relevancy" => 0, 'session_count' => $sessioncount, 'contrib_count' => $contribcount);
			}
		}
		else {
			foreach($results as $result) {
				
				$contribcount = (isset($result['contrib_count'])) ? $result['contrib_count'] : countNumberOfContributors($result['experiment_id']);
				$sessioncount = (isset($result['session_count'])) ? $result['session_count'] : countNumberOfContributors($result['experiment_id']);
			    
				$output[] = array("meta" => $result, "tags" => array(), "relevancy" => 0, 'session_count' => $sessioncount, 'contrib_count' => $contribcount);
			}
		}
		
		return $output;
	}
	else {
		return count($results);
	}
}

function browseExperimentsByRecent($page = 1, $limit = 10, $override = false) {
	global $db;
	
	$sqlCmd = "SELECT 	experiments.*, 
						(experiments.rating / experiments.rating_votes ) AS rating_comp,
						users.firstname AS owner_firstname, 
						users.lastname AS owner_lastname
						FROM experiments 
						LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
						WHERE experiments.hidden = 0
						AND experiments.activity = 0";


	$sqlCmd.= " ORDER BY experiments.timemodified DESC";
	
	$output = $db->query($sqlCmd);

	if($db->numOfRows) {		
		return packageBrowseExperimentResults($output, $page, $limit, $override);
	}
		
	return false;
} 

function browseExperimentsByRating($page = 1, $limit = 10, $override = false) {
	global $db;
	
	$sqlCmd = "SELECT 	experiments.*, 
						(experiments.rating / experiments.rating_votes ) AS rating_comp,
						users.firstname AS owner_firstname, 
						users.lastname AS owner_lastname
						FROM experiments 
						LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
						WHERE experiments.hidden = 0
						AND experiments.activity = 0
						ORDER BY (experiments.rating / experiments.rating_votes) DESC, experiments.timemodified DESC";
	
	$output = $db->query($sqlCmd);

	if($db->numOfRows) {
		return packageBrowseExperimentResults($output, $page, $limit, $override);
	}
		
	return false;
}

function browseExperimentsByFeatured($page = 1, $limit = 10, $override = false) {
	global $db;
	
	$sqlCmd = "SELECT 	experiments.*, 
						(experiments.rating / experiments.rating_votes ) AS rating_comp,
						users.firstname AS owner_firstname, 
						users.lastname AS owner_lastname
						FROM experiments 
						LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
						WHERE experiments.featured = 1
						AND experiments.hidden = 0
						AND experiments.activity = 0
						ORDER BY experiments.timemodified DESC";
	
	$output = $db->query($sqlCmd);

	if($db->numOfRows) {
		return packageBrowseExperimentResults($output, $page, $limit, $override);
	}
		
	return false;
}

function browseExperimentsByPopular($page = 1, $limit = 10, $override = false) {
	global $db;
	
	$sqlCmd = "SELECT 	experiments.*, 
						(experiments.rating / experiments.rating_votes ) AS rating_comp,
						users.firstname AS owner_firstname, 
						users.lastname AS owner_lastname
						FROM experiments 
						LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
						WHERE experiments.hidden = 0
						AND experiments.activity = 0
						ORDER BY experiments.timemodified DESC";
	
	$output = $db->query($sqlCmd);

	if($db->numOfRows) {
		for($i = 0; $i < count($output); $i++) {
			$contrib_count = countNumberOfContributors($output[$i]['experiment_id']);
			$output[$i]['contrib_count'] = $contrib_count;
		}
				
		uasort($output, 'contrib_cmp');
		//$output = array_reverse($output);
		
		return packageBrowseExperimentResults($output, $page, $limit, $override);
	}
		
	return false;
}

function browseExperimentsByActivity($page = 1, $limit = 10, $override = false)  {
	global $db;
	
	$sqlCmd = "SELECT 	experiments.*, 
						(experiments.rating / experiments.rating_votes ) AS rating_comp,
						users.firstname AS owner_firstname, 
						users.lastname AS owner_lastname
						FROM experiments 
						LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
						WHERE experiments.hidden = 0
						AND experiments.activity = 0";
	
	$output = $db->query($sqlCmd);

	if($db->numOfRows) {
		for($i = 0; $i < count($output); $i++) {
			$session_count = countNumberOfSessions($output[$i]['experiment_id']);
			$output[$i]['session_count'] = $session_count;
		}
		
		uasort($output, 'session_cmp');
		$output = array_reverse($output);
				
		return packageBrowseExperimentResults($output, $page, $limit, $override);
	}
		
	return false;
}

function browseExperimentsByUser($user_id) {
	global $db;
	
	$output = $db->query("SELECT 	experiments.*,
	                                experiments.timecreated AS `timeobj`,
									(experiments.rating / experiments.rating_votes ) AS rating_comp
									FROM experiments 
									WHERE experiments.owner_id = {$user_id} 
									AND experiments.activity = 0
									ORDER BY experiments.timecreated DESC");
	
	if($db->numOfRows) {
		return $output;
	}

	return false;
}

function getNumberOfExperiments() {
	global $db;
	
	$output = $db->query("SELECT COUNT(*) AS `count` FROM experiments");
	
	return $output[0]['count'];
}

function getTags(){
	global $db;
	$output = $db->query("SELECT tagIndex.value AS `tag` FROM tagIndex GROUP BY tagIndex.value");
	return $output;
}

function getTagId($value) {
	global $db;
	
	$sql = "SELECT tagIndex.tag_id FROM tagIndex WHERE tagIndex.value = '{$value}'";
	$output = $db->query($sql);
	
	if(count($output) > 0) {
		return $output[0]['tag_id'];
	}
	
	return -1;
}

function addTag($value, $weight = 0) {
	global $db;
	
	$sql = "INSERT INTO `tagIndex` (`value`, `weight`) VALUES('{$value}', '{$weight}')";
	$output = $db->query($sql);
	
	if($db->numOfRows) {
		return $db->lastInsertId();
	}
	
	return false;
}

function addTagToExperiment($eid, $tid, $weight = 0) {
	global $db;
	
	$sql = "INSERT INTO `tagExperimentMap` (`experiment_id`, `tag_id`, `weight`) VALUES('{$eid}', '{$tid}', '{$weight}')";
	$output = $db->query($sql);
	
	if($db->numOfRows) {
	    updateTimeModifiedForExperiment($eid);
		return true;
	}
	
	return false;
}

function addTagsToExperiment($eid, $tag_list) {
	global $db;
	
	$zipped_list = array();
	foreach($tag_list as $t){
		if(!array_key_exists($t['value'], $zipped_list)) {
			$zipped_list[$t['value']] = $t['weight'];
		}
		else {
			// If the tag is already present with a lower weight, then replace with higher weight
			if($zipped_list[$t['value']] <  $t['weight']) {
				$zipped_list[$t['value']] = $t['weight'];
			}
		}
	}
	
	$tag_cache = array();
	foreach($zipped_list as $k => $v) {
		
		$id = -1;
		if(!array_key_exists($k, $tag_cache)) {
			
			$id = getTagId($k);
			if($id == -1) {
				$id = addTag($k, (($v > 0) ? 1 : 0));
			}
			
			$tag_cache[$k] = $id;
		}
		else {
			$id = $tag_cache[$k];
		}
		
		addTagToExperiment($eid, $id, $v);
	}
	
	updateTimeModifiedForExperiment($eid);
	
	return true;
}

function getTagsForExperiment($eid) {
	global $db;
	
	$sql = "SELECT tagIndex.value AS `tag` FROM tagIndex, tagExperimentMap WHERE tagIndex.weight = 1 AND tagIndex.tag_id = tagExperimentMap.tag_id AND tagExperimentMap.experiment_id = {$eid} AND tagExperimentMap.weight = 2";
	$output = $db->query($sql);

	if($db->numOfRows) {
		return $output;
	}

	return false;
}

function updateTimeModifiedForExperiment($eid) {
    global $db;
    
    $sql = "UPDATE `experiments` SET experiments.timemodified = NOW() WHERE experiments.experiment_id = {$eid}";
    $db->query($sql);
    
    return true;
}

function getAllExperiments() {
    global $db;
    
    $sql = "SELECT * FROM experiments";
    $query = $db->query($sql);
    
    if($db->numOfRows) {
        return $query;
    }
    
    return false;
}

function isNameRequired($eid){
    global $db;
    $sql = "SELECT req_name FROM experiments WHERE experiments.experiment_id={$eid}";
    $query = $db->query($sql);
    return $query[0]["req_name"];
}

function isLocationRequired($eid){
    global $db;
    $sql = "SELECT req_location FROM experiments WHERE experiments.experiment_id={$eid}";
    $query = $db->query($sql);
    return $query[0]["req_location"];
}

function isProcedureRequired($eid){
    global $db;
    $sql = "SELECT req_procedure FROM experiments WHERE experiments.experiment_id={$eid}";
    $query = $db->query($sql);
    return $query[0]['req_procedure'];
}

function getSessionPrefix($eid){
    global $db;
    $sql = "SELECT name_prefix FROM experiments WHERE experiments.experiment_id={$eid}";
    $query = $db->query($sql);
    return $query[0]['name_prefix'];
}

function getExperimentLocation($eid){
    global $db;
    $sql = "SELECT location FROM experiments WHERE experiments.experiment_id={$eid}";
    $query = $db->query($sql);
    return $query[0]['location'];
}

function getSessionOffset($eid) {
    global $db;
    $sql = "SELECT DISTINCT COUNT(*) FROM experimentSessionMap WHERE experiment_id={$eid};";
    $query = $db->query($sql);
    return $query[0]['COUNT(*)'];
}

function getExpOwner($sid) {
    global $db;
    
    $sql = "SELECT owner_id FROM experiments WHERE experiment_id=( SELECt experiment_id FROM experimentSessionMap WHERE session_id={$sid} )";
    $query = $db->query($sql);
    
    if($db->numOfRows) {
        return $query;
    }
    
    return false;
}

function closeExperiment($eid){
    global $db;
    
    $sql = "UPDATE experiments SET closed=1 where experiment_id={$eid}";

    $query = $db->query($sql);

    return true;
    
}

function uncloseExperiment($eid){
    global $db;
    $sql = "UPDATE experiments SET closed=0 where experiment_id={$eid}";

    $query = $db->query($sql);

    return true;
}

function experimentClosed($eid){
    global $db;
    
    $sql = "SELECT closed FROM experiments WHERE experiment_id={$eid}";
    $query = $db->query($sql);
    
    if($query[0]['closed'] == 1){
        return true;
    } else {
        return false;
    }

}

function getExperimentOwner($eid){

    global $db;
    
    $sql = "SELECT owner_id FROM experiments WHERE experiment_id={$eid} LIMIT 0,1";
    
    $output = $db->query($sql);

    if($db->numOfRows) {
        return $output[0]['owner_id'];
    }

    return false;

}

function updateExperimentImage($url,$eid){
    global $db;
    
    $sql = "UPDATE experiments SET exp_image='{$url}' WHERE experiment_id={$eid}";

    $output = $db->query($sql);

    return true;
}

//Promote an experiment as iSENSE recommended.
function recommendExperiment($eid){
    global $db;

    $sql = "UPDATE experiments SET recommended=1 WHERE experiment_id={$eid}";

    $output = $db->query($sql);

    return true;
}

//Demote an experiment from iSENSE recommended status.
function unrecommendExperiment($eid){
    global $db;

    $sql = "UPDATE experiments SET recommended=0 WHERE experiment_id={$eid}";

    $output = $db->query($sql);

    return true;
}


//One function to rule them all
function browseExperiments($page=1, $limit=10, $hidden=0,$featured="off",$recommended="off", $tags= null, $sort = "recent"){
    global $db;

    $result = array();

<<<<<<< HEAD
    $sql = "SELECT DISTINCT *,
            (experiments.rating/experiments.rating_votes) as rating_comp
            FROM experiments ";
=======
    $sql = "SELECT DISTINCT *
    FROM experiments ";
>>>>>>> efe36b78dc342dac34018ad8542ab932e959183a

    if($tags){
        $sql .= ", tagExperimentMap, tagIndex";
    }

    $sql .= " WHERE experiments.hidden = {$hidden} ";

    if($featured == 'on'){
        $sql .= " AND experiments.featured=1 ";
    }

    if($recommended == 'on'){
        $sql .= " AND experiments.recommended=1";
    }

    if($tags){

        $sql .= " AND ( ";

        //If there are tags we want to search by each of them.
        $tags = explode(" ", $tags);

        for($i=0;$i<count($tags);$i++){

            $sql .= " (tagIndex.value like '%{$tags[$i]}%'
            AND tagIndex.tag_id = tagExperimentMap.tag_id
            AND experiments.experiment_id = tagExperimentMap.experiment_id
            AND tagIndex.weight=1)";

            if($i < count($tags) -1){
                $sql .= " OR ";
            }
        }

        $sql .= " )";
    }

    if($sort == "rating"){
        $sql .= " ORDER BY experiments.rating/experiments.rating_votes";
    } elseif ($sort == "activity"){
        $sql .= " ORDER BY experiments.timemodified";
    } else {
        $sql .= " ORDER BY experiments.timecreated";
    }

    $sql .= " DESC ";

    $result = $db->query($sql);

    $keys = array();
    $tmp = array();

    for($i = 0; $i < count($result); $i++) {
        $tmpKey = $result[$i]['experiment_id'];
        if (in_array($tmpKey,$keys)){
        } else {
            $keys[count($keys)] = $tmpKey;
            $tmp[count($tmp)] = $result[$i];
        }
    }
    
    $result = $tmp;

    return packageBrowseExperimentResults($result, $page,$limit,false);

}

?>
