<?php

require_once '../includes/config.php';

if(isset($_GET['action']) && isset($_GET['follower']) && isset($_GET['followee'])) {
    
    $result = false;
    $action = safeString($_GET['action']);
    $follower = safeString($_GET['follower']);
    $followee = safeString($_GET['followee']);
    
    switch($action) {
        
        case "follow":
            $result = addGraphEdge($follower, $followee);
            break;
            
        case "unfollow":
            $result = deleteGraphEdge($follower, $followee);
            break;
    }
    
    if($result) {
        echo "worked!";
    }
    else {
        echo "failed!";
    }
}

?>