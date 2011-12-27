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
