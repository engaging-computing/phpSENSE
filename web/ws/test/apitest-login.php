<?php

global $success_count;
global $failure_count;

function loginTest($user,$pass){
    //The target for this test
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
        return json_decode($result,true);
}

//--------------------------------------------------------------------------------------------------------------------

//Tests login
echo "<h1>Login Test</h1>";

//Correct user/pass
echo "<h2>Testing login with correct username and password....</h2>";

$login_response = loginTest('sor','sor');

if ($login_response['status'] == 200) {
    $uid = $login_response['data']['uid'];
    $session_key = $login_response['data']['session'];
    echo "<div class='success'>SUCCESS</div>, Successful login. UID: ";
    echo "<a href=\"http://localhost/profile.php?id=" . $uid ."\">" . $uid . "</a>";
    echo ", Session Key: " . $session_key . '<br>';
    $success_count++;
} else {
    echo "<div class='failure'>FAILURE</div>, Unsuccessful login. JSON: ";
    print_r($login_response);
    echo "<br>";
    $failure_count++;
}

echo '<br>';

//Incorrect user/pass
echo "<h2>Testing login with incorrect username and password....</h2>";

$login_response = loginTest('sor','');

if ($login_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unsuccessful login.<br>";
    $success_count++;
} elseif ($login_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Successful login. JSON: ";
    print_r($login_response);
    echo "<br>";
    $failure_count++;
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($login_response);
    echo "<br>";
    $failure_count++;
}

echo '<hr>';

?>