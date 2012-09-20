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
			$vises = getSavedVisByExperiment($out['experiment_id']);

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
	
	$db->query("UPDATE savedVises SET hidden = 1 WHERE vid = {$vid}");
	
	if($db->numOfRows) {
	    updateTimeModifiedForVis($vid);
		return true;
	}
	
	return false;
}

function showVis($vid) {
	global $db;
	
	$db->query("UPDATE savedVises SET hidden = 0 WHERE vid = {$vid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function storeSavedVis($owner,$experiment,$title,$description,$data,$globals){
    global $db;

    $sql = "INSERT INTO savedVises (owner_id,experiment_id,title,description,data,globals) VALUES ({$owner},{$experiment},\"{$title}\",\"{$description}\",\"{$data}\",\"{$globals}\")";

    $db->query($sql);

    if($db->numOfRows){
        return $db->lastInsertId();
    }

    return false;
}

function getSavedVis($vid){
    global $db;

    $sql = "SELECT * FROM savedVises where vid = {$vid}";

    $output = $db->query($sql);

    return $output;
}

function getSavedVisDesc($vid){
    global $db;

    $sql = "SELECT vid, title, description, hidden, owner_id, timecreated as `timeobj`
            FROM savedVises where vid = {$vid}";

    $output = $db->query($sql);

    if ($db->numOfRows) {
        return $output[0];
    }
    
    return false;
}

function getSavedVisByExperiment($experiment_id){
    global $db;

    $sql = "SELECT vid, title, description, hidden, owner_id, timecreated as `timeobj`
            FROM savedVises where experiment_id = {$experiment_id}";

    $output = $db->query($sql);

    return $output;
}

function getSavedVisByOwner($owner_id){
    global $db;

    $sql = "SELECT vid, title, description, hidden, owner_id, timecreated as `timeobj`
            FROM savedVises where owner_id = {$owner_id}";
    
    $output = $db->query($sql);
    
    if($db->numOfRows){
        return $output;
    }
    return false;
}

function getAllSavedVises(){
    global $db;

    $sql = "SELECT vid, title, description, hidden, owner_id, timecreated as `timeobj`
            FROM savedVises";
    
    $output = $db->query($sql);

    return $output;
}


?>
