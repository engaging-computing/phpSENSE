<?php

$session_id = null;

function loginTest(){
    global $session_id;
    
    //The target for this test
    $target = "http://isensedev.cs.uml.edu/ws/api.php?method=login&username=sor&password=sor";

    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'username' => 'sor',
        'password' => 'sor'
    ));

    //Run curl to get the response
    $result = curl_exec($ch);

    //Parse the response to an associative array
    $x = json_decode($result,true);

    //---------------------------------------------------------------------------------
    //This block is incomplete, you will have to deal with several different responses.
    
    //The status of the request
    $status = $x['status'];

    //The session_id needed to test the other stuff
    $session_id = $x['data']['session'];
    //---------------------------------------------------------------------------------
    
    //Close curl
    curl_close($ch);

    //If we got a successful response return true
    if($status == 200){
        return true;
    } return false;
}

//Just runs the login test function
if(loginTest()){
    echo "Successful login ".$session_id;
} else {
    echo "Could not login";
}
?>