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

$sid = -1;
$created = false;
$title = "Done";

if(isset($_GET['id'])) {
    
    $sid = (int) safeString($_GET['id']);
    
    // Get the experiment meta data
    $values = getSession($sid);
    $exp = getExpOwner($sid);
        
    $smarty->assign('owner', $exp[0]['owner_id'] );
    
    $exp = getExperimentNameFromSession($sid);
    
    $title = "Edit Session > {$exp['name']} > {$values['name']}";
    
    // Output to view
    $smarty->assign('values', $values);
    
    $hidden_val = isSessionHidden($sid);
    $smarty->assign('hideme', $hidden_val);
    
        
}

if(isset($_POST['session_create'])) {
    $sid = (int) safeString($_POST['id']);
    
    $org_values = getSession($sid);
    
    $city = safeString($_POST['session_citystate']);
    $street = safeString($_POST['session_street']);
    
    
    if(isset($_POST['session_hidden']) && strtolower($_POST['session_hidden']) == 'on'){
        $hidden_val = 0;
    } else {
        $hidden_val = 1;
    }
    
    $cur_user = $session->getUser();
    $tmp = getSession($sid);

    if( $cur_user['user_id'] != $tmp['owner_id'] and !$cur_user['administrator'] ){

        $values = array(
            'name' => $tmp['name'],
            'description' => $tmp['description'],
            'city' => $tmp['city'],
            'street' => $tmp['street'],
            'finalized' => $hidden_val,
            'latitude' => $tmp['latitude'],
            'longitude' => $tmp['longitude']
            
        );
    
    } else {
        
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

if(isset($hidden_val)) {
    $smarty->assign('hide', $hidden_val);
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
