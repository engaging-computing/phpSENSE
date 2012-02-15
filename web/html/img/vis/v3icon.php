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

list( $width, $height ) = getimagesize('./marker.png');

$img = imagecreatetruecolor( $width, $height );

$srcimg = imagecreatefrompng('./marker.png');

$white = imagecolorexact($img, 255, 255, 255);
imagefilledrectangle($img, 0, 0, 20, 34, $white);

imagecopyresampled($img, $srcimg, 0, 0, 0, 0, $width, $height, $width, $height );

imagecolortransparent($img, $white);

if( isset($_REQUEST['color']) ) {
    $cols = $_REQUEST['color'];
    $cols = explode(",", $_REQUEST['color']);
    $color = imagecolorallocate( $img, $cols[0], $cols[1], $cols[2] );
}
else
    $color = imagecolorallocate( $img, 0, 101, 255 );

imagefill($img, 5, 5, $color);

header("Content-type: image/png");

imagepng( $img );
imagecolordeallocate( $img, $color );
imagedestroy( $srcimg );
imagedestroy( $img );

?>