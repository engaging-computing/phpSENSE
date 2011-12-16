<?php

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
    		$vises		= getVisByExperiment($id);
    		$tags 		= getTagsForExperiment($id);
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
		$smarty->assign('sessions', 	$sessions);
		
		$votes = ($meta['rating_votes'] == 0) ? 1 : $meta['rating_votes'];
		$rating = $meta['rating'] / $votes;
		$smarty->assign('rating', round($rating, 0));
		
		
		
	}
	else {
		array_push($errors, "The experiment you're looking for is no longer available.");
	}
}
else {
	array_push($errors, "The experiment you're looking for is no longer available.");
}

$smarty->assign('id',		$id);
$smarty->assign('activity',	$is_activity);
$smarty->assign('user', 	$session->getUser());
$smarty->assign('title', 	$title);
$smarty->assign('head', 	$smarty->fetch('parts/experiment-head.tpl'));
$smarty->assign('content', 	$smarty->fetch('experiment.tpl'));
$smarty->display('skeleton.tpl')

?>
