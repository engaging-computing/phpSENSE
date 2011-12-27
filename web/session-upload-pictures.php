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
require_once LIB_DIR . 'S3.php';
require_once LIB_DIR . 'simpleimage.php';


ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '20M');

$sid = -1;
$id = -1;
$done = false;
$meta = array();
$sessionmeta = array();
$ownerid = -1;
$errors = array();
$collabs = array();
$values = array();

if(isset($_GET['sid'])) {
	$sid = safeString($_GET['sid']);
	$id  = getSessionExperimentId( $sid );
}

if($meta = getExperiment($id)) {
	$ownerid = $meta['owner_id'];
	$collabs = getExperimentCollaborators($session->userid, $id);
}

$sessionmeta = getSession($sid);

if(isset($_POST['picture_create'])) {

	$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);

	$vtitle = '';
	if(isset($_POST['picture_name'])) { $vtitle = safeString($_POST['picture_name']); }
	if($vtitle == '') { array_push($errors, 'The picture title can not be blank.'); }
	$values['vtitle'] = $vtitle;

	$description = '';
	if(isset($_POST['picture_description'])) { $description = safeString($_POST['picture_description']); }
	if($description == '') { array_push($errors, 'The picture description can not be blank.'); }
	$values['description'] = $description;
	
	$count = -1;
	if(isset($_POST['row_count'])) { $count = (int) safeString($_POST['row_count']); }
	if($count == -1) { array_push($errors, 'An internal form error occurred, please try again later.'); }
	$values['count'] = $count;
	
	$url = "http://s3.amazonaws.com/" . AWS_IMG_BUCKET;
	
	if(count($errors) == 0) {
		
		for($i = 1; $i < ($count+1); $i++) {
			if(count($errors) == 0) {
				$item = 'picture_file_'.$i;

				if(isset($_FILES[$item])) {
					$target_path = '/tmp/';
					$target_path = $target_path . basename($_FILES[$item]['name']); 

					// Mime Type Check
					$mime = mime_content_type($_FILES[$item]['tmp_name']);

					$accepted_mimes = array(
											'image/jpeg',
											'image/gif',
											'image/png',
										);

					if(!in_array($mime, $accepted_mimes)) {
						array_push($errors, 'You attempted to upload an unsupported image type, or you did not select an image. Please try uploading a JPEG, PNG or GIF.');
					}

					if(count($errors) == 0) {
						if(move_uploaded_file($_FILES[$item]['tmp_name'], $target_path)) {

							//bookmark

							$resizeimage = new SimpleImage();
							$resizeimage->load($target_path);
							
							if( $resizeimage->getWidth() > $resizeimage->getHeight() ){

								if( $resizeimage->getWidth() > 800 )

									$resizeimage->resizeToWidth( 800 );

							} else {

								if( $resizeimage->getHeight() > 800 )

									$resizeimage->resizeToHeight( 800 );

							}

							$resizeimage->save($target_path);

							$ext = substr($target_path, strpos($target_path, ".")+1);
							$ext = str_replace(".", "", $ext);

							$name = $meta['experiment_id'] . '_' . $session->userid . '_' . time() . '_' . $i . '.' . $ext;
							$s3->putObjectFile($target_path, AWS_IMG_BUCKET, $name, S3::ACL_PUBLIC_READ);
							$provider_url = $url . '/' . $name;

							createImageItemWithSessionId($session->userid, $meta['experiment_id'], $sid, "iSENSE - " . $vtitle, $description, 'Amazon S3', $name, $provider_url, AWS_IMG_BUCKET, 1);
						}
					}
					else {
						unlink($_FILES[$item]['tmp_name']);
					}
				}
				else {
					array_push($errors, 'An error occurred while uploading your file');
				}
			}
		}
	}
	
	$done = (count($errors) == 0);
}


$smarty->assign('values', 	$values);
$smarty->assign('errors', 	$errors);
$smarty->assign('done', 	$done);
$smarty->assign('sid',		$sid);
$smarty->assign('id',		$id);
$smarty->assign('title', 	ucwords($sessionmeta['name']) . ' - Add New Pictures');
$smarty->assign('user', 	$session->getUser());
$smarty->assign('content', 	$smarty->fetch('session-upload-pictures.tpl'));
$smarty->display('skeleton.tpl');

?>
