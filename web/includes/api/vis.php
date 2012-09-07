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

function createNewVis($uid, $eid, $name, $desc, $sessions, $url_params, $is_activity = 0, $hidden = 0) {
	global $db;
	
	$db->query("INSERT INTO visualizations (`owner_id`, `experiment_id`, `name`, `description`, `url_params`, `is_activity`, `hidden`, `timecreated`, `timemodified`) VALUES({$uid}, {$eid}, '{$name}', '{$desc}', '{$url_params}', {$is_activity}, {$hidden}, NOW(), NOW())");
	
	if($db->numOfRows) {
		$vid = $db->lastInsertId();
		
		foreach($sessions as $session) {
			addSessionToVis($vid, $session);
		}
		
		return $vid;
	}
	
	return false;
}

function addSessionToVis($vid, $sid) {
	global $db;
	
	$output = $db->query("INSERT INTO visualizationSessionMap (`vis_id`, `session_id`) VALUES({$vid}, {$sid})");
	
	if($db->numOfRows) {
	    updateTimeModifiedForVis($vid);
		return true;
	}
	
	return false;
}

function getVisByUser($uid) {
	global $db;
	
	$output = $db->query("SELECT 	visualizations.vis_id, 
									visualizations.owner_id, 
									visualizations.experiment_id, 
									visualizations.name, 
									visualizations.url_params,
									visualizations.hidden, 
									visualizations.timecreated,
									visualizations.description,
									visualizations.timecreated AS `timeobj`,
									experiments.name AS `experiment_name`, 
									experiments.description  AS `experiment_description`,
									experiments.hidden AS `experiment_hidden`
									FROM visualizations, experiments 
									WHERE visualizations.experiment_id = experiments.experiment_id
									AND visualizations.owner_id = {$uid}
									AND visualizations.is_activity = 0");
	
	if($db->numOfRows) {
		for($i = 0; $i < count($output); $i++) {
			$output[$i]['sessions'] = implode("+",getSessionsForVis($output[$i]['vis_id']));
		}
		
		return $output;
	}
	
	return false;
}

function getVisByExperiment($eid) {
	global $db;
	
	$output = $db->query("SELECT * FROM visualizations WHERE experiment_id = {$eid} AND is_activity = 0");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getVisByTag($tag) {
	global $db;
	
	$sql = "SELECT * FROM tagIndex, tagExperimentMap, experiments
			WHERE tagIndex.value = '{$tag}' 
			AND tagIndex.tag_id = tagExperimentMap.tag_id 
			AND experiments.experiment_id = tagExperimentMap.experiment_id 
			AND tagIndex.weight = 1";
			
	$output = $db->query($sql);
	
	if($db->numOfRows) {
		$results = array();
		
		foreach($output as $out) {
			$vises = getVisByExperiment($out['experiment_id']);

			if($vises) {
				foreach($vises as $v) {
					array_push($results, $v);
				}
			}
		}
				
		return $results;
	}
	
	return false;
}

function hideVis($vid) {
	global $db;
	
	$db->query("UPDATE visualizations SET hidden = 1 WHERE vis_id = {$vid}");
	
	if($db->numOfRows) {
	    updateTimeModifiedForVis($vid);
		return $db->lastInsertId();
	}
	
	return false;
}

function showVis($vid) {
	global $db;
	
	$db->query("UPDATE visualizations SET hidden = 0 WHERE vis_id = {$vid}");
	
	if($db->numOfRows) {
		return $db->lastInsertId();
	}
	
	return false;
}

function getSessionsForVis($vid) {
	global $db;
	
	$output = $db->query("SELECT session_id FROM visualizationSessionMap WHERE vis_id = {$vid}");
	
	$data = array();
	if($db->numOfRows) {
		foreach($output as $o) {
			array_push($data, $o['session_id']);
		}
		
		return $data;
	}
	
	return false;
}

function getVisById($vid) {
	global $db;
	
	$output = $db->query("SELECT * FROM visualizations WHERE vis_id = {$vid} LIMIT 0, 1");
	
	if($db->numOfRows) {
		
		$compress = array();
		$sessions = getSessionsForVis($vid);
		/*
		foreach($sessions as $sid) {
			$compress[] = $sid['session_id'];
		}
		
		$compress = implode(" ", $compress);
		*/
		$output[0]['sessions'] = implode(" ", $sessions);
		
		return $output[0];
	}
	
	return false;
}

function getNumberOfVisualizations() {
	global $db;
	
	$output = $db->query("SELECT COUNT(*) AS `count` FROM visualizations WHERE visualizations.is_activity = 0");
	
	return $output[0]['count'];
}

function updateTimeModifiedForVis($vid) {
    global $db;
    
    $sql = "UPDATE `visualizations` SET visualizations .timemodified = NOW() WHERE visualizations.vis_id = {$vid}";
    $db->query($sql);
    
    return true;
}

function addFeaturedVis($vid) {
	global $db;
	
	$db->query("UPDATE visualizations SET featured = 1 WHERE vis_id = {$vid}");
	
	$output = $db->query("SELECT name from visualizations WHERE vis_id = {$vid}");
	
	$name = "";
	if($db->numOfRows) {
		$name = $output[0]['name'];
	}
	/*
	if($name != "") {
		publishToTwitter('Latest Featured Visualization: "'.$name.'" - http://isense.cs.uml.edu/rc1/vis.php?id='.$vid);
	}
	*/
	updateTimeModifiedForVis($vid);
	
	return true;
}

function removeFeaturedVis($vid) {
	global $db;
	
	$db->query("UPDATE visualizations SET featured = 0 WHERE vis_id = {$vid}");
	
	updateTimeModifiedForVis($vid);
	
	return true;
}

function setVisualizationPictureId( $vid, $purl ){
    global $db;
    
    $db->query("UPDATE visualizations SET picture_url = \"{$purl}\" WHERE vis_id = {$vid}");
    
    return true;
    
}


/**Functions for saved vises for HIGHVIS **/
function storeSavedVis($owner,$experiment,$title,$description,$json){
    global $db;

    $sql = "INSERT INTO savedVises (owner_id,experiment_id,title,description,json) VALUES ({$owner},{$experiment},\"{$title}\",\"{$description}\",\"{$json}\")";

    $db->query($sql);

    if($db->numOfRows){
        return true;
    }

    return false;
}

function getSavedVisByExperiment($experiment_id){
    global $db;

    $sql = "SELECT * FROM savedVises where experiment_id = {$experiment_id}";

    $output = $db->query($sql);

    return $output;
}

function getSavedVisByOwner($owner_id){
    global $db;

    $sql = "SELECT * FROM savedVises where owner_id = {$owner_id}";
    
    $output = $db->query($sql);

    return $output;
}

function getAllSavedVises(){
    global $db;

    $sql = "SELECT * FROM savedVises";
    
    $output = $db->query($sql);

    return $output;
}


?>
