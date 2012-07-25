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
error_log("IM LOGGING HERE:  " . $src);
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
