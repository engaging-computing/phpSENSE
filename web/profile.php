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