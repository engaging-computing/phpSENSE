<?php

require_once 'includes/config.php';

$id = $session->userid;
$is_owner = true;
$errors = array();

if(isset($_GET['id'])) {
	if($_GET['id'] != $id) {
		$id = (int) safeString($_GET['id']);
		$is_owner = false;
	}
}

$userdata = getUserDetails($id);

$data = array();
$output = array();

// Grab the users meta data
$data['vis'] = getVisByUser($id);
$data['session'] = browseMySessions($id);
$data['experiment'] = browseExperimentsByUser($id);
$data['image'] = getImagesByUser($id);
$data['video'] = getVideosByUser($id);

/*
$data['activity_responses'] = array();
if($is_owner) {
    $data['activity_responses'] = getFeedFromFollowers($id, -1);
}
else {
    
}
*/

$data['activity_responses'] = getResponsesFromUser($id);

// Compile the user's media
foreach($data as $key => $value) {
	if(is_array($value)) {
		foreach($value as $v) {
			$v['type'] = $key;
			$output[] = $v;
		}
	}
}

usort($output, 'timeobj_cmp');

// Grab the user's graph
$followers = getFollowers($id);
$following = getFollowing($id);
$is_following = "No";
if($id == $session->userid) {
    $is_following = "You";
}
else {
    $is_following = (doesFollow($session->userid, $id)) ? "Yes" : "No";
}

$smarty->assign('is_following', $is_following);
$smarty->assign('followers', $followers);
$smarty->assign('following', $following);
$smarty->assign('is_owner', $is_owner);

$smarty->assign('results', $output);
$smarty->assign('errors', $errors);
$counts = array('sessions' => count($data['session']), 
				'experiments' => count($data['experiment']), 
				'vises' => count($data['vis']),
				'images' => count($data['image']),
				'videos' => count($data['video']));
$smarty->assign('counts', $counts);

if($is_owner) {
    $smarty->assign('title', 'Your Profile');
}
else {
    $smarty->assign('title', $userdata['firstname'] . ' ' . $userdata['lastname'] . '\'s Profile');
}

$smarty->assign('userdata', $userdata);

$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('profile.tpl'));
$smarty->display('skeleton.tpl');

?>