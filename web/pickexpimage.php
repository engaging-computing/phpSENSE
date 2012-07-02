<?php

require_once 'includes/config.php';

$debug = true;

$expid = $_GET['id'];

$exp = getExperiment( $expid );

$imgurl = "";

$images = array();

$test = getExperimentDefaultPicture( $exp['activity_for'] );

$test = $exp['activity_for'];

//$test = $expid;


if(isset($exp['exp_image'])) {
    $imgurl = $exp['exp_image'];
} else {
    
    /*
    if( $exp['activity'] == 0 ){
        $imgurl = getExperimentDefaultPicture( $expid );
    } else {
        $imgurl = getExperimentDefaultPicture( $exp['activity_for'] );
    }
    */
}

if( $exp['activity'] == 0 ){
    $images = getImagesForExperiment( $expid );
} else {
    $images = getImagesForExperiment( $exp['activity_for'] );
}

if($images == false) $images = array();

array_push($images, array(provider_url => "http://s3.amazonaws.com/isenseimgs/429_162_1340048099_1.png"));

$smarty->assign('user',     $session->getUser());
$smarty->assign('title',    'Featured Experiment Image');
$smarty->assign('images',   $images);
$smarty->assign('expid',    $expid);
$smarty->assign('test',     $test);
$smarty->assign('imgurl',   $imgurl);
$smarty->assign('content',  $smarty->fetch('pickexpimage.tpl'));

$smarty->display('skeleton.tpl');

?>
