<?php

require_once 'includes/config.php';

$targ_w = $targ_h = 150;
$jpeg_quality = 90;

if(isset($_GET['h'])) {
	$targ_h = (int) $_GET['h'];
}

if(isset($_GET['w'])) {
	$targ_w = (int) $_GET['w'];
}

$src = "";
if(isset($_GET['id']) && !isset($_GET['type'])) {
	
	$user_id = (int)$_GET['id'];

	// This is just bad....
	$src = PIC_DIR . $user_id . '.jpg';
	if(!file_exists($src)) {
		$src = PIC_DIR . $user_id . '.png';
		if(!file_exists($src)) {
			$src = PIC_DIR . $user_id . '.gif';
			if(!file_exists($src)) {
				$src = dirname(__FILE__) . '/html/img/user.jpg';
			}
		}
	}
}
else if(isset($_GET['type']) && isset($_GET['id'])) {
	$id = safeString($_GET['id']);
	$imgs = getImagesForExperiment($id);

	if(count($imgs) > 1) {
		header('Location: ' . $imgs[0]['provider_url']);
	}
}
else if(isset($_GET['url'])) {
	$src = $_GET['url'];
}

if($src != "") {
	list($width, $height, $type) = getimagesize($src);

	switch($type) {
		case IMAGETYPE_JPEG:
			$img_r = imagecreatefromjpeg($src);
			break;

		case IMAGETYPE_GIF:
			$img_r = imagecreatefromgif($src);
			break;

		case IMAGETYPE_PNG:
			$img_r = imagecreatefrompng($src);
			break;
	}

	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

	imagecopyresampled($dst_r, $img_r, 0, 0, 0, 0, $targ_w, $targ_h, $width, $height);

	header('Content-type: image/jpeg');
	imagejpeg($dst_r, null, $jpeg_quality);
}

?>