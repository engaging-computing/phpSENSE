<?php

require_once 'includes/config.php';

$sid = -1;
$created = false;
$title = "Done";

if(isset($_GET['id'])) {
    
    $sid = (int) safeString($_GET['id']);
    
    // Get the experiment meta data
    $values = getSession($sid);
    
    $title = "Edit Session - {$values['name']}";
    
    // Output to view
    $smarty->assign('values', $values);
    
}

if(isset($_POST['session_create'])) {
    $sid = (int) safeString($_POST['id']);
    
    $org_values = getSession($sid);
    
    $city = safeString($_POST['session_citystate']);
    $street = safeString($_POST['session_street']);
    
    $hidden_val = ((isset($_POST['session_hidden'])) ? safeString($_POST['session_hidden']) : "off");
    if(strcasecmp($hidden_val, "on") == 0) {
        $hidden_val = 0;
    }
    else {
        $hidden_val = 1;
    }
    
    $values = array(
                    'name' => safeString($_POST['session_name']),
                    'description' => safeString($_POST['session_description']),
                    'city' => $city,
                    'street' => $street,
                    'finalized' => $hidden_val
                );
                
    if(($city != $org_values['city']) || ($street != $org_values['street'])) {
        $cords = getLatAndLon($street, $city, "United States");
        $lat = $cords[1];
		$lon = $cords[0];
		
		$values['latitude'] = $lat;
		$values['longitude'] = $lon;
    }
    
    updateSession($sid, $values);

    $created = true;
    $title = "Successfully Edited Session";
    
}

// Process the images for displayi

global $db;

$images = array();

$images = getImagesForSession($sid);

$image_urls = array();

if($images) {
        foreach($images as $img) {
                array_push($image_urls, array('source' => $img['provider_url'], 'set_url' => $img['provider_url']));
        }   
}


$smarty->assign('pictures',     $image_urls);
$smarty->assign('sid',		$sid);
$smarty->assign('id',		getSessionExperimentId($sid) );
$smarty->assign('created',	$created);
$smarty->assign('user',		$session->getUser());
$smarty->assign('title',	$title);
$smarty->assign('content',	$smarty->fetch('session-edit.tpl'));
$smarty->display('skeleton.tpl');


?>
