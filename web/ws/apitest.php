<?php

//Log in token used to authenticate a user
$session_key = null;

//Session id for experiment
$session_id = null;




function loginTest($user,$pass){
    global $session_id;
    
    //The target for this test
    //$target =  "isensedev.cs.uml.edu/ws/api.php?method=login";
    $target =  "localhost/ws/api.php?method=login";

    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'username' => $user,
        'password' => $pass
    ));

    //Run curl to get the response
    $result = curl_exec($ch);

    //Close curl
    curl_close($ch);

    //Parse the response to an associative array
    $parsed_response = json_decode($result,true);

    return $parsed_response;
 /*   //---------------------------------------------------------------------------------
    
    //The status of the request
    $status =  $parsed_response['status'];
    
    
    
    if ($status == 600){
        return false;
    }
    elseif ($status == 200)
    {
    //The session_id needed to test the other stuff
        $session_key = $x['data']['session'];
        return true;
    //---------------------------------------------------------------------------------
    }*/
}

function createSessionTest($exp){
    echo "Testing createSession on exp: ". $exp . "<br>";
    global $session_key;
    global $session_id;
    
    //The target for this test
    //$target = "http://isensedev.cs.uml.edu/ws/api.php?method=createSession";
    $target = "localhost/ws/api.php?method=createSession";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'session_key' => $session_key,
        'eid' => $exp,
        'name' => 'Name Test'.time(),
        'description' => 'Procedure Test'.time(),
        'street' => '1 university ave',
        'city' => 'Lowell MA',
        'country' => 'USA'
     )); 

    //Run curl to get the response
    $result = curl_exec($ch);
    //Close curl
    curl_close($ch);

    //Parse the response to an associative array
    $parsed_response = json_decode($result,true);
    
    $status =  $parsed_response['status'];
    
    if($status == 200){ //200 OK
        echo "200 OK<br>";
        $session_id = $parsed_response['data']['sessionId'];
        return true;
    } elseif ($status == 400) {// 400 Closed Experiment
        echo " Experiment: " . $exp . " is closed!<br>";
        return false;
    } elseif ($status == 600) {// 600 Bad Request
        echo "BAD REQUEST<br>";
        return false;
    } else {
        return false;
    }

}




//Just runs the login test function
echo "Testing login on correct user/pass....<br>";
$login_response = loginTest('sor','sor');
//print_r($login_response);
if ($login_response['status'] == 200){
    echo "PASS, Successful login. UID: ". $login_response['data']['uid'] . ", Session Key: " . $login_response['data']['session'] . '<br><br>';
}
else{
    echo "FAIL, Status: " . $login_response['status'] . '<br><br>';
}


echo "Testing login on incorrect user/pass....<br>";
$login_response = loginTest('sor','sors');
if ($login_response['status'] == 600){
    echo "PASS, Unsuccessful login ...give info";
}
elseif ($login_response['status'] == 400){
    echo "FAIL, Successful login ....give info";
}

/*
if(loginTest('sor','sor')){
    echo "PASS, Successful login ".$session_id;
} else {
    echo "FAIL, Could not login";
}

echo '<br><br>';


*/


/*
//Test unclosed experiment
if(createSessionTest(346)){
    echo "PASS, Successfuly created session on open experiment";
} else {
    echo "FAIL, Could not create session on open experiment ";
}

echo '<br><br>';

//Test closed experiment
if(createSessionTest(345)){
    echo "FAIL, Created session on close experiment";
} else {
    echo "PASS, Could not create session on a closed experiment";
}*/

?>