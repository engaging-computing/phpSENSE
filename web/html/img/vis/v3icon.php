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
imagecolordeallocate( $color );
imagedestroy( $srcimg );
imagedestroy( $img );

?>