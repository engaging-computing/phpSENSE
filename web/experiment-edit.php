<?php

require_once 'includes/config.php';

$eid = -1;
$created = false;
$title = "Done";

if(isset($_GET['id'])) {
    
    $eid = (int) safeString($_GET['id']);
    
    // Get the experiment meta data
    $values = getExperiment($eid);
    
    $title = "Edit Experiment - {$values['name']}";
        
    $tags = getTagsForExperiment($eid);

    $tag_string = "";    
    
    foreach($tags as $tag) {
        $tag_string .= "{$tag['tag']}, ";
    }

    // Remove last ", " from $tag_string
    $tag_string = substr( $tag_string, 0, strlen( $tag_string ) - 2 );

    
    $values['tags'] = $tag_string;
    
    // Output to view
    $smarty->assign('values', $values);
    
}

if(isset($_POST['experiment_create'])) {
    $eid = (int) safeString($_POST['id']);
    
    $values = array(
                    'name' => safeString($_POST['experiment_name']),
                    'description' => safeString($_POST['experiment_description'])
                );
    
    updateExperiment($eid, $values);
    
    // Something busted here, also need to account for checkboxes
    $tags = getTagsForExperiment($eid);
    $tag_list = array();
    $new_tags = array();
    
    foreach($tags as $tag) {
        $tag_list[] = $tag['tag'];
    }
    
    $tag_submit = safeString($_POST['experiment_tags']);
    $tag_submit = explode(",", $tag_submit);
    
    foreach($tag_submit as $tag) {
        if(!in_array($tag, $tag_list) && !in_array($tag, $tag_list)) {
            $new_tags[] = array('value' => $tag, 'weight' => 2);
        }
    }
    
    addTagsToExperiment($eid, $new_tags);
    
    $created = true;
    $title = "Successfully Edited Experiment";
    
}

$smarty->assign('eid', $eid);
$smarty->assign('created', $created);
$smarty->assign('user', $session->getUser());
$smarty->assign('title', 'Edit Experiment');
$smarty->assign('content', $smarty->fetch('experiment-edit.tpl'));
$smarty->display('skeleton.tpl');


?>