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
require_once 'includes/config.php';

$id = -1;
$errors = array();
$title = "Experiment Not Found";
$time = false;
$is_activity = false;
if(isset($_GET['id'])) {	
	$id = (int) safeString($_GET['id']);
	$meta = getExperiment($id);
	$is_activity = ($meta['activity'] == 1);
	
	if(count($meta) > 0) {
		
		// Grab some meta data
		$title = $meta['name'];
		$ownerid = $meta['owner_id'];
		$meta['create_diff'] = dateDifference(time(), strtotime($meta['timecreated']));
		$meta['mod_diff'] = dateDifference(time(), strtotime($meta['timemodified']));
		
		// Make calls to pull data from db
		$fields = array();
		$vises = array();
		$tags = array();
		$videos = array();
		$images = array();
		$sessions = array();
		$collabs = array();
		
		if($is_activity) {
		    $fields     = getFields($meta['activity_for']);
		    $tags       = getTagsForExperiment($meta['activity_for']);
		    $sessions 	= getSessionsForExperiment($id);
		    $vises	= getResponsesForActivity($id);
		}
		else {
                    $fields 	= getFields($id);
                    $vises	= getSavedVisByExperiment($id);
                    $tags 	= getTagsForExperiment($id);
                    $videos 	= getVideosForExperiment($id);
                    $images 	= getImagesForExperiment($id);
                    $collabs 	= getExperimentCollaborators($ownerid, $id);
                    $sessions 	= getSessionsForExperiment($id);
		}
		
		
		// Process the images for display
		$image_urls = array();
		if($images) {
			foreach($images as $img) {
				array_push($image_urls, array('source' => $img['provider_url'], 'set_url' => $img['provider_url']));
			}
		}
		
		// Push data to smarty template
		$smarty->assign('meta',		$meta);
		$smarty->assign('tags',		$tags);
		$smarty->assign('vises', 	$vises);
		$smarty->assign('fields', 	$fields);
		$smarty->assign('pictures',	$image_urls);
		$smarty->assign('expimages',	$images);
		$smarty->assign('videos', 	$videos);
		$smarty->assign('collabs', 	$collabs);
		
		//Get user avatars
                $userAvatars = array();
                foreach ($sessions as $index=>$ses) {
                    $sessions[$index]['owner_avatar'] = getUserAvatar($ses['owner_id'], 32);
                }
		$smarty->assign('sessions', 	$sessions);
		
		$votes = ($meta['rating_votes'] == 0) ? 1 : $meta['rating_votes'];
		$rating = $meta['rating'] / $votes;
		$smarty->assign('rating', round($rating, 0));
		$smarty->assign('newvis',1);
		
		
	}
	else {
		array_push($errors, "The experiment you're looking for is no longer available.");
	}
}
else {
	array_push($errors, "The experiment you're looking for is no longer available.");
}

//Build the qr codes string
$qrcode = "http://".$_SERVER['SERVER_NAME']."/experiment.php?id=".$id;
$filename = "data/qrs/".$id.".png";

//Generate the qr code
QRcode::png($qrcode,$filename);

//Assign the qr code
$smarty->assign('qrcode', $filename);


$smarty->assign('id',		$id);
$smarty->assign('activity',	$is_activity);
$smarty->assign('user', 	$session->getUser());
$smarty->assign('title', 	$title);

if(strpos($_SERVER['HTTP_USER_AGENT'],'Android')!= true){
    $smarty->assign('head', 	$smarty->fetch('parts/experiment-head.tpl'));
    $smarty->assign('content', 	$smarty->fetch('experiment.tpl'));
    $smarty->display('skeleton.tpl');
} else {
    $smarty->display('mobile/experiment.tpl');
}


?>
