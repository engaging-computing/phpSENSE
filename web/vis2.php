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

$errors = array();

$aid = -1;
$vis = array();
$is_saved = false;
$is_activity = false;
$activity_meta = array();
$link = "";

if(isset($_GET['sessions'])) {
	
	if(isset($_GET['aid'])) {
	    $aid = safeString($_GET['aid']);
	    $is_activity = true;
	    $activity_meta = getActivity($aid);
	}
	
	if(isset($_GET['is_saved'])) {
	    $is_saved = $_GET['is_saved'];
	}
	
	if(isset($_GET['vid'])) {
	    $vis = getVisById($_GET['vid']);
	}
	
	$state = "";
	if(isset($_GET['state'])) {
		$state = urlencode($_GET['state']);
	}
	$smarty->assign('state', $state);
	
	$smarty->assign('sessions', urlencode($_GET['sessions']));
	$sessions = explode(" ", $_GET['sessions']);
	
	if($is_activity) {
	    $name = 'Activity: ' . $activity_meta['name'];
	    $name = array("name" => $name);
	}
	else {

	    if(count($sessions) == 1) {

    		$session_data = getSession($sessions[0]);
    		$session_name = $session_data['name'];

    		$name = getExperimentNameFromSession($sessions[0]);
    		$link = $name['name'];
    		//$link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a>: '. $session_name;
    	}
    	else {
    		$link = $name = getExperimentNameFromSession($sessions[0]);
    		$link = $name['name'];
    		//$link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a>';
    	}

	}
}

$smarty->assign('aid', $aid);
$smarty->assign('vis', $vis);
$smarty->assign('is_saved', $is_saved);
$smarty->assign('activity', $is_activity);
$smarty->assign('activity_meta', $activity_meta);

$smarty->assign('link', $link);
$smarty->assign('title', $name['name']);
$smarty->assign('errors', $errors);

$smarty->assign('user', $session->getUser());

$smarty->assign('head', $smarty->fetch('parts/vis-head-flot.tpl'));

$smarty->assign('content', $smarty->fetch('vis.tpl'));
$smarty->display('skeleton.tpl');

?>
