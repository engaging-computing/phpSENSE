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
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '20M');

$id = -1;
$done = false;
$ownerid = -1;
$errors = array();
$collabs = array();
$values = array();

if(isset($_GET['id'])) {
	$id = safeString($_GET['id']);
}

if($meta = getExperiment($id)) {
	$ownerid = $meta['owner_id'];
	$collabs = getExperimentCollaborators($session->userid, $id);
}

if(isset($_POST['video_create'])) {
	
	/* Setup Zend */
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata_YouTube');
	Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

	$appId = 'iSENSE';
	$clientId = 'iSENSE';
	$httpClient = null;

	try {
		$authURL = 'https://www.google.com/youtube/accounts/ClientLogin';
		$httpClient = Zend_Gdata_ClientLogin::getHttpClient(	$username = YOUTUBE_USER,
																$password = YOUTUBE_PASS,
																$service = 'youtube',
																$client = null,
																$source = 'iSENSE',
																$loginToken = null,
																$loginCaptcha = null,
																$authURL);
	}
	catch(Zend_Gdata_App_AuthException $e) {
		array_push($errors, $e->getMessage());
	}


	$yt = new Zend_Gdata_YouTube($httpClient, YOUTUBE_APPID, YOUTUBE_CLIENTID, YOUTUBE_KEY);
	
	$vtitle = '';
	if(isset($_POST['video_name'])) { $vtitle = 'iSENSE - ' . safeString($_POST['video_name']); }
	if($vtitle == '') { array_push($errors, 'The video title can not be blank.'); }
	$values['title'] = $vtitle;
	
	$description = '';
	if(isset($_POST['video_description'])) { $description = safeString($_POST['video_description']); }
	if($description == '') { array_push($errors, 'The video description can not be blank.'); }
	$values['description'] = $description;
	
	$citystate = '';
	if(isset($_POST['video_citystate'])) { $citystate = safeString($_POST['video_citystate']); }
	if($citystate == '') { array_push($errors, 'The video city and states can not be blank.'); }
	$values['citystate'] = $citystate;
	
	$street = '';
	if(isset($_POST['video_street'])) { $street = safeString($_POST['video_street']); }
	if($street == '') { array_push($errors, 'The video street scan not be blank.'); }
	$values['street'] = $street;
	
	if(!isset($_FILES['video_file'])) {
		array_push($errors, 'No session file was entered.');
	}
	
	if(strpos($_FILES['video_file']['type'], "video") === FALSE) {
	    array_push($errors, 'The file you uploaded is not a video');
	}
	
	if(count($errors) == 0) {
		/* Might want to check the move code, this could cause colisions */
		$target_path = '/tmp/';
		$target_path = $target_path . basename($_FILES['video_file']['name']); 
		if(!move_uploaded_file($_FILES['video_file']['tmp_name'], $target_path)) {
		    array_push($errors, 'Error uploading file!');
		}
	}
	
	if(count($errors) == 0) {
		$videoEntry = new Zend_Gdata_YouTube_VideoEntry();

		$fs = $yt->newMediaFileSource($target_path);
		$fs->setContentType($_FILES['video_file']['type']);
		$fs->setSlug($_FILES['video_file']['name']);

		$videoEntry->setMediaSource($fs);

		$videoEntry->setVideoTitle($vtitle);
		$videoEntry->setVideoDescription($description);
		$videoEntry->setVideoCategory('Education');

		$videoEntry->setVideoTags('isense');

		$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
		$newEntry = null;
		
		try {
			$newEntry = $yt->insertEntry($videoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
		} 
		catch (Zend_Gdata_App_HttpException $httpException) {
		  	array_push($errors, $httpException->getRawResponseBody());
		} 
		catch (Zend_Gdata_App_Exception $e) {
		    array_push($errors, $e->getMessage());
		}
		
		if(count($errors) == 0) {
			$done = true;
			$videoId = $newEntry->getVideoId();
			$url = 'http://www.youtube.com/watch?v=' . $videoId;
			createVideoItem($session->userid, $meta['experiment_id'], $vtitle, $description, $street, $citystate, $videoId, $url);
			publishToTwitter('New Video Added To: "'.$meta['name'].'" http://isense.cs.uml.edu/experiment.php?id=' . $meta['experiment_id']);
			
		}
	}
}

$smarty->assign('meta', $meta);
$smarty->assign('values', $values);
$smarty->assign('errors', $errors);
$smarty->assign('done', $done);
$smarty->assign('title', ucwords($meta['name']) . ' - Add New Video');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('upload-videos.tpl'));
$smarty->display('skeleton.tpl');

?>
