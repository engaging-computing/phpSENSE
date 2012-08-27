<?php

    require_once '../includes/config.php';

 
    if( isset($_POST['file']) && isset($_POST['matched_columns']) && isset($_POST['eid'])) {
        $session_key = $_COOKIE['isense_login'];
        $uid = getUserIdFromSessionToken($session_key);
        $eid = $_POST['eid'];
        $name = $_POST['sname'];
        $description = $_POST['sdesc'];
        $city = $_POST['sloc'];
        // Don't touch these
        $default_read = 1;
        $default_contribute = 1;
        $finalized = 1;
        //Shuffle the data to correct columns.
        $data = shuffleColumns($_POST['file'], $eid, $_POST['matched_columns']);      

        $data = fixTime($data,$eid);    
        
        $sid = createSession(array('uid' => $uid, 'session' => $session_key), $eid, $name, $description, $street, $city, $country, $default_read, $default_contribute, $finalized);

        if( putData($eid, $sid, $data) ) {
            echo $sid;
        } 
    }
    



    
?>
