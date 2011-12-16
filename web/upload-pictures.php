<?php

require_once 'includes/config.php';
require_once LIB_DIR . 'S3.php';
require_once LIB_DIR . 'simpleimage.php';


ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '20M');


$id = -1;
$done = false;
$meta = array();
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
						array_push($errors, 'You attempted to upload an unsupported image type. Please try uploading a JPEG, PNG or GIF.');
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

							createImageItem($session->userid, $meta['experiment_id'], "iSENSE - " . $vtitle, $description, 'Amazon S3', $name, $provider_url, AWS_IMG_BUCKET, 1);
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

$smarty->assign('values', 		$values);
$smarty->assign('errors', 		$errors);
$smarty->assign('done', 		$done);
$smarty->assign('title', ucwords($meta['name']) . ' - Add New Pictures');
$smarty->assign('user', $session->getUser());
$smarty->assign('content', $smarty->fetch('upload-pictures.tpl'));
$smarty->display('skeleton.tpl');

?>
