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

function createVideoItem($uid, $eid, $title, $description, $street, $citystate, $provider_id, $provider_url, $published = 1, $country = "United States", $provider="youtube") {
	global $db;
	
	$cords = getLatAndLon($street, $citystate, $country);
	$latitude = $cords[1];
	$longitude = $cords[0];
	
	$output = $db->query("INSERT INTO videos (`owner_id`, `experiment_id`, `title`, `description`, `street`, `city`, `country`, `latitude`, `longitude`, `provider`, `provider_id`, `provider_url`, `published`, `uploaded`) VALUES({$uid}, {$eid}, '{$title}', '{$description}', '{$street}', '{$citystate}', '{$country}', '{$latitude}', '{$longitude}', '{$provider}', '{$provider_id}', '{$provider_url}', {$published}, NOW())");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function deleteVideoItem($vid) {
	global $db;
	
	$output = $db->query("UPDATE videos SET videos.published = 0 WHERE videos.video_id = {$vid}");
	
	if($db->numOfRows)  {
		return true;
	}
	
	return false;
}

function getVideosForExperiment($eid) {
	global $db;
	
	$output = $db->query("SELECT videos.*, users.firstname, users.lastname FROM videos, users WHERE videos.owner_id = users.user_id AND videos.experiment_id = {$eid}");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getVideosByUser($uid) {
	global $db;
	
	$output = $db->query("SELECT 	videos.title,
		 						 	videos.experiment_id,
								 	videos.video_id,
									videos.description,
									videos.provider_url,
									videos.provider_id,
									videos.uploaded,
									videos.uploaded  AS `timeobj`,
									experiments.name,
									experiments.description
									FROM videos, experiments 
									WHERE videos.experiment_id  = experiments.experiment_id
									AND videos.owner_id = {$uid}");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function createImageItem($uid, $eid, $title, $description, $provider, $provider_id, $provider_url, $provider_group_id, $published = 1) {
	global $db;
	
	$output = $db->query("INSERT INTO pictures (`owner_id`, `experiment_id`, `title`, `description`, `provider`, `provider_id`, `provider_url`, `provider_group_id`, `published`) VALUES({$uid}, {$eid}, '{$title}', '{$description}', '{$provider}', '{$provider_id}', '{$provider_url}', '{$provider_group_id}', '{$published}')");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function createImageItemSes($uid, $eid, $sid, $title, $description, $provider, $provider_id, $provider_url, $provider_group_id, $published = 1) {
	global $db;
	
	$output = $db->query("INSERT INTO pictures (`owner_id`, `experiment_id`, `session_id`, `title`, `description`, `provider`, `provider_id`, `provider_url`, `provider_group_id`, `published`) VALUES({$uid}, {$eid}, {$sid}, '{$title}', '{$description}', '{$provider}', '{$provider_id}', '{$provider_url}', '{$provider_group_id}', '{$published}')");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function createImageItemWithSessionId($uid, $eid, $sid, $title, $description, $provider, $provider_id, $provider_url, $provider_group_id, $published = 1) {
	global $db;
	
	$output = $db->query("INSERT INTO pictures (`owner_id`, `experiment_id`, `session_id`, `title`, `description`, `provider`, `provider_id`, `provider_url`, `provider_group_id`, `published`) VALUES({$uid}, {$eid}, {$sid}, '{$title}', '{$description}', '{$provider}', '{$provider_id}', '{$provider_url}', '{$provider_group_id}', '{$published}')");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function deleteImageItem() {
	
}

function getImagesForExperiment($eid) {
	global $db;
	
	$output = $db->query("SELECT * FROM pictures WHERE pictures.experiment_id = {$eid} ORDER BY created DESC LIMIT 15");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getImagesForSession($sid) {
	global $db;
	
	$output = $db->query("SELECT * FROM pictures WHERE pictures.session_id = {$sid}");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getImagesByUser($uid) {
	global $db;
	
	$output = $db->query("SELECT 	pictures.title,
		 						 	pictures.experiment_id,
								 	pictures.picture_id,
									pictures.description,
									pictures.provider_url,
									pictures.provider_id,
									pictures.created,
									pictures.created AS `timeobj`,
									experiments.name,
									experiments.description
									FROM pictures, experiments 
									WHERE pictures.experiment_id = experiments.experiment_id
									AND pictures.owner_id = {$uid}");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getVisDefaultPicture( $vid ){
    global $db;
    
    $pic = $db->query(  "SELECT pcs.provider_url 
                         FROM visualizations AS vis, pictures AS pcs 
                         WHERE vis.featured = 1 
                         AND vis.vis_id = {$vid} 
                         AND vis.experiment_id = pcs.experiment_id 
                         AND pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = vis.experiment_id)
                         LIMIT 0, 1");
    
    if($db->numOfRows) {
        return $pic[0]['provider_url'];
    }
    
    return false;
    
}

function getExperimentDefaultPicture( $eid ){
    global $db;
    
    $pic = $db->query(  "SELECT pcs.provider_url 
                         FROM experiments AS exp, pictures AS pcs 
                         WHERE exp.experiment_id = {$eid} 
                         AND pcs.experiment_id = {$eid}
                         AND pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = exp.experiment_id)
                         LIMIT 0, 1");
    
    if($db->numOfRows) {
        return $pic[0]['provider_url'];
    }
    
    return false;
    
}

// This doesn't get featured experiments with photos
function getExperimentsWithPhotos($limit = 3) {
	global $db;
	
	$output = $db->query("SELECT 	pictures.title,
		 						 	pictures.experiment_id,
								 	pictures.picture_id,
									pictures.description,
									pictures.provider_url,
									pictures.provider_id,
									pictures.created AS `timecreated`,
									experiments.name,
									experiments.description,
									experiments.owner_id,
									users.firstname,
									users.lastname
									FROM pictures, experiments, users 
									WHERE pictures.experiment_id = experiments.experiment_id
									AND experiments.owner_id = users.user_id
									GROUP BY experiments.experiment_id
									LIMIT 0, {$limit}");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getFeaturedExperimentsWithPhotos($limit = 6) {
	global $db;

    $sql = "SELECT experiments.experiment_id,
                   experiments.exp_image,
                   experiments.name,
                   users.user_id,
                   users.firstname,
                   users.lastname,
                   users.private
            FROM experiments,users
            WHERE featured=1
            AND exp_image IS NOT NULL
            AND users.user_id = experiments.owner_id
            ORDER BY timecreated DESC
            LIMIT {$limit}";

    $output = $db->query($sql);
	
	//Filter private last names
	foreach($output as $key => $o) {
	    if($o['private']) {
	        $output[$key]['lastname'] = substr(ucfirst($o['lastname']), 0, 1);
	    }
	}
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getFeaturedExperimentsWithPhotosShort($limit = 6){
    global $db;
    
    $experiments = $db->query(  "SELECT DISTINCT
                                    exp.experiment_id,
                                    exp.name,
                                    exp.owner_id,
                                    usr.firstname,
                                    usr.lastname,
                                    pcs.provider_url 
                                 FROM experiments AS exp, users AS usr, pictures AS pcs
                                 WHERE exp.featured = 1 
                                 AND exp.owner_id = usr.user_id 
                                 AND exp.experiment_id = pcs.experiment_id
                                 AND pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = exp.experiment_id)
                                 ORDER BY timecreated DESC
                                 LIMIT 0, {$limit};");
    
    if($db->numOfRows) {
        return $experiments;
    }
    
    return false;
}

function getFeaturedVisualizationsWithPhotos($limit = 6){
    global $db;
    
    $experiments = $db->query(  "SELECT DISTINCT vis.vis_id, vis.name, vis.owner_id, usr.firstname, usr.lastname, pcs.provider_url 
                                 FROM visualizations AS vis, users AS usr, pictures AS pcs 
                                 WHERE vis.featured = 1 
                                 AND vis.owner_id = usr.user_id 
                                 AND vis.experiment_id = pcs.experiment_id
                                 AND pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = vis.experiment_id)
                                 ORDER BY timecreated DESC
                                 LIMIT 0, {$limit};");
    
    if($db->numOfRows) {
        return $experiments;
    }
    
    return false;
}

function getFeaturedExperimentsWithPhotosBigThree($limit = 3){
    global $db;
    
    $experiments = $db->query(  "SELECT DISTINCT
                                    exp.experiment_id,
                                    exp.name,
                                    exp.owner_id,
                                    exp.picture_url,
                                    usr.firstname,
                                    usr.lastname,
                                    pcs.provider_url 
                                 FROM experiments AS exp, users AS usr, pictures AS pcs
                                 WHERE exp.featured = 1 
                                 AND exp.owner_id = usr.user_id 
                                 AND exp.experiment_id = pcs.experiment_id
                                 AND (pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = exp.experiment_id)
                                     OR
                                     (exp.picture_url != NULL ))
                                 AND exp.activity = 0
                                 ORDER BY timecreated DESC
                                 LIMIT 0, {$limit};");
    
    if($db->numOfRows) {
        return $experiments;
    }
    
    return false;
}

function getFeaturedVisualizationsWithPhotosBigThree($limit = 3){
    global $db;
    
    $experiments = $db->query(  "SELECT DISTINCT vis.vis_id, 
                                                 vis.name, 
                                                 vis.owner_id, 
                                                 vis.picture_url, 
                                                 usr.firstname, 
                                                 usr.lastname, 
                                                 pcs.provider_url 
                                 FROM visualizations AS vis, users AS usr, pictures AS pcs 
                                 WHERE vis.featured = 1 
                                 AND vis.owner_id = usr.user_id 
                                 AND vis.experiment_id = pcs.experiment_id
                                 AND (pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = vis.experiment_id)
                                     OR
                                     ( vis.picture_url != NULL ))
                                 ORDER BY timecreated DESC
                                 LIMIT 0, {$limit};");
    
    if($db->numOfRows) {
        return $experiments;
    }
    
    return false;
}

function getFeaturedActivitiesWithPhotosBigThree($limit = 3){
    global $db;
    
    $experiments = $db->query(  "SELECT DISTINCT
                                    exp.experiment_id,
                                    exp.name,
                                    exp.owner_id,
                                    exp.picture_url,
                                    usr.firstname,
                                    usr.lastname,
                                    pcs.provider_url 
                                 FROM experiments AS exp, users AS usr, pictures AS pcs
                                 WHERE exp.featured = 1 
                                 AND exp.owner_id = usr.user_id 
                                 AND exp.activity_for = pcs.experiment_id
                                 AND (pcs.created >= (SELECT MAX(created) FROM pictures AS tmp WHERE tmp.experiment_id = exp.activity_for)
                                     OR
                                     ( exp.picture_url != NULL ))
                                 AND exp.activity = 1
                                 ORDER BY timecreated DESC
                                 LIMIT 0, {$limit};");
    
    if($db->numOfRows) {
        return $experiments;
    }
    
    return false;    
}

function createLinkItem() {
	
}

function deleteLinkItem() {
	
}

function getLinksForExperiment() {
	
}

?>
