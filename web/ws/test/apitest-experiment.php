<?php

function uploadImageToSessionTest($exp){
    global $session_key;
    
    //The target for this test
    $target = "localhost/ws/api.php?method=uploadImageToSession";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'image' => ,
        'eid' => $exp,
        'sid' => 
        'session_key' => $session_key, 
        'img_name' => 'Automated Test'.time(),
        'img_description' => 'Automated Testing Proc'.time()

        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

?>
