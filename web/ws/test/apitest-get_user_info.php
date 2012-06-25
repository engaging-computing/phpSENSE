<?php

function getUserProfileTest($id){
    //The target for this test
    $target =  "localhost/ws/api.php?method=getUserProfile";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $id
        ));
        
        //Run curl to get the response
        $result = curl_exec($ch);
        
        //Close curl
        curl_close($ch);
        
        //Parse the response to an associative array
        return json_decode($result,true);
    
}

function getExperimentByUserTest($id){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperimentByUser";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $id
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

function getVisByUserTest($id){
    //The target for this test
    $target = "localhost/ws/api.php?method=getVisByUser";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $id
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl

        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);       
}

function getImagesByUserTest($id){
    //The target for this test
    $target = "localhost/ws/api.php?method=getImagesByUser";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $id
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl

        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);       
}

function getVideosByUserTest($id){
    //The target for this test
    $target = "localhost/ws/api.php?method=getVideosByUser";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $id
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

function getSessionsByUserTest($id){
    //The target for this test
    $target = "localhost/ws/api.php?method=getSessionsByUser";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $id
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl

        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);       
}

?>