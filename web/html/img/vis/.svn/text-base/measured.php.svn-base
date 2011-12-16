<?php

$triangle = array( 0, 20,
                   20, 20,
                   10, 34 
            );

$ses_triangle = array( 2, 22,
                       18, 22,
                       10, 32    
            );

$img = imagecreatetruecolor( 20, 34 );

if( isset($_REQUEST['color']) ) {
    $cols = $_REQUEST['color'];
    $cols = explode(",", $_REQUEST['color']);
    $color = imagecolorexact( $img, $cols[0], $cols[1], $cols[2] );
} else
    $color = imagecolorexact( $img, 0, 101, 255 );

if( isset($_REQUEST['value']) ) {
    $val = $_REQUEST['value'];
}

$white = imagecolorexact($img, 255, 255, 255);
$black = imagecolorexact($img, 0, 0, 0);
$yellow = imagecolorexact($img, 255, 255, 0);

imagefilledrectangle($img, 0, 0, 20, 34, $white);
imagefilledrectangle($img, 0, 0, 20, 20, $black);
imagefilledpolygon($img, $triangle, 3, $black);
imagefilledpolygon($img, $ses_triangle, 3, $color);

imagefilledrectangle($img, 1,20 - $val, 18, 20, $yellow );


imagecolortransparent($img, $white);

header("Content-type: image/png");

imagepng( $img );
imagecolordeallocate( $color );
imagecolordeallocate( $white );
imagecolordeallocate( $black );
imagedestroy( $img );

?>