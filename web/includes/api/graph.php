<?php

function addGraphEdge($follower, $followee) {
    global $db;
    
    $sql = "INSERT INTO graph (`follower`, `followee`) VALUES({$follower}, {$followee})";
    $results = $db->query($sql);
    
    if($db->numOfRows) {
        return true;
    }
    
    return false;
}

function deleteGraphEdge($follower, $followee) {
    global $db;
    
    $sql = "DELETE FROM graph WHERE graph.follower = {$follower} AND graph.followee = {$followee} AND graph.blocked = 0";
    $results = $db->query($sql);
    
    if($db->numOfRows) {
        return true;
    }
    
    return false;
}

// Gets people following userid
function getFollowers($uid) {
    global $db;
    
    $sql = "SELECT users.*, graph.* FROM graph, users WHERE graph.followee = {$uid} AND graph.follower = users.user_id AND graph.blocked = 0";
    $results = $db->query($sql);
    
    if($db->numOfRows) {
        return $results;
    }
    
    return false;
}

// Get who the userid is following
function getFollowing($uid) {
    global $db;
    
    $sql = "SELECT users.*, graph.* FROM graph, users WHERE graph.follower = {$uid} AND graph.followee = users.user_id AND graph.blocked = 0";
    $results = $db->query($sql);
    
    if($db->numOfRows) {
        return $results;
    }
    
    return false;
}
 
function doesFollow($follower, $followee) {
    global $db;
    
    $sql = "SELECT graph.* FROM graph WHERE graph.follower = {$follower} AND graph.followee = {$followee} AND graph.blocked = 0 LIMIT 0,1";
    $results = $db->query($sql);
    
    if($db->numOfRows) {
        return true;
    }
    
    return false;
}

function createBlockOnEdge($follower, $followee) {
    global $db;
    
    $sql = "UPDATE graph SET graph.blocked = 1 WHERE graph.follower = {$follower} AND graph.followee = {$followee}";
    $results = $db->query($sql);
    
    if($db->numOfRows) {
        return true;
    }
    
    return false;
}

function getFeedFromFollowers($uid, $page = 1, $limit = 10) {
    global $db, $session;
    
    // Get activities published by user
   $ats = getActivitiesFromUser($uid);
    
    // Get the users the uid is following
    $following = getFollowing($uid);
    
    // Flatten the uid list
    $fgraph = array();
    foreach($following as $f) {
        if(!in_array($fgraph, $f['user_id'])) {
            $fgraph[] = $f['user_id'];
        }
    }
        
    // Pull out their experiment ids
    $responses = array();
    for($i = 0; $i < count($ats) - 1; $i++) {
        $aid = $ats[$i]['experiment_id'];
        $v = getResponsesForActivity($aid);

        if(count($v) > 0) {
            for($j = 0; $j < count($v); $j++) {
                array_push($responses, $v[$j]);
            }
        }
    }
        
    return $responses;
}

?>