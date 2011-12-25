<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
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
 -->
<?php

function createActivity($eid, $sessions, $uid, $name, $description) {
    global $db;
        
    $sql = "INSERT INTO experiments (`owner_id`, `name`, `description`, `activity`, `activity_for`) VALUES('{$uid}', '{$name}', '{$description}', 1, {$eid})";
    
    $results =  $db->query($sql);
    if($db->numOfRows) {
        
        // Remap Sessions
        $eid = $db->lastInsertId();
        $sessions = split(",", $sessions);
        foreach($sessions as $session) {
           addSessionMapping($eid, $session);
        }
        
        return $eid;
    }
    
    return false;
}


function getActivity($aid) {
    global $db;
										
	$output = $db->query("SELECT experiments.*, users.firstname, users.lastname FROM experiments, users WHERE experiments.owner_id = users.user_id AND experiments.activity = 1 AND experiments.experiment_id = {$aid}");
	
	if($db->numOfRows) {
		return $output[0];
	}
	
	return false;
}

function isActivity($eid) {
    global $db;    

    $sql = "SELECT experiments.experiment_id from experiments WHERE experiments.experiment_id = {$eid} ANd experiments.activity = 1 LIMIT 0,1";
    $output = $db->query($sql);
    
    if($db->numOfRows) {
        return true;
    }
    
    return false;
}

function getActivitiesFromUser($uid) {
    global $db;
    
    $sql = "SELECT experiments.*, (experiments.rating / experiments.rating_votes ) AS `rating_comp` FROM experiments WHERE experiments.hidden = 0 AND experiments.activity = 1 AND experiments.owner_id = {$uid} ORDER BY experiments.timemodified DESC";
	
	$output = $db->query($sql);
	
	if($db->numOfRows) {
	    return $output;
	}
	
	return false;
}

function browseActivities($page = 1, $limit = 10) {
    global $db;
    
    $sql = "SELECT  experiments.*,
                    (experiments.rating / experiments.rating_votes ) AS rating_comp,
                    users.firstname AS owner_firstname, 
					users.lastname AS owner_lastname
					FROM experiments
					LEFT JOIN ( users ) ON ( users.user_id = experiments.owner_id ) 
					WHERE experiments.hidden = 0
					AND experiments.activity = 1
					ORDER BY experiments.timecreated DESC";
					
	$results = $db->query($sql);
	
	if($db->numOfRows) {
	    return packageBrowseActivityResults($results, $page, $limit);
	}
	
	return false;
}

function getResponsesForActivity($eid) {
    global $db;

	$output = $db->query("SELECT experiments.name  AS `exp_name`, visualizations.*, users.firstname AS owner_firstname, users.lastname AS owner_lastname FROM visualizations, users, experiments WHERE users.user_id = visualizations.owner_id AND experiments.experiment_id = visualizations.experiment_id AND visualizations.experiment_id = {$eid} AND visualizations.is_activity = 1");

	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getResponsesFromUser($uid) {
    global $db;

	$output = $db->query("SELECT experiments.name  AS `exp_name`, visualizations.*, users.firstname AS owner_firstname, users.lastname AS owner_lastname FROM visualizations, users, experiments WHERE users.user_id = visualizations.owner_id AND experiments.experiment_id = visualizations.experiment_id AND visualizations.is_activity = 1 AND visualizations.owner_id = {$uid}");

	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}


function packageBrowseActivityResults($results, $page = 1, $limit = 10, $override = false) {
    global $db;
    
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
	
	return $output;
}
?>