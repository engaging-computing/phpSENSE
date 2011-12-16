<?php

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

function packageBrowseVisualizationsResults($results, $page = 1, $limit = 10) {
	
	if($page != -1) {
		$output = array();
		$offset = ($page - 1) * $limit;
		$results =  array_splice($results, $offset, $limit);

		foreach($results as $result) {
			$output[$result['vis_id']] = array("meta" => $result, "tags" => array(), "relevancy" => 0);
		}

		return $output;
	}
	else {
		return count($results);
	}
}

function browseVisualizationsByTimeCreated($page = 1, $limit = 10) {
	global $db;
	
	$sql = "SELECT visualizations.*, users.firstname, users.lastname FROM visualizations, users WHERE visualizations.owner_id = users.user_id AND visualizations.is_activity = 0 ORDER BY visualizations.timecreated DESC";
	$results = $db->query($sql);
	
	if($db->numOfRows) {
		return packageBrowseVisualizationsResults($results, $page, $limit);
	}
	
	return false;
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

?>