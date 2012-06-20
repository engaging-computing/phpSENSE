<html>
<head>
<title>iSenseDev Automated Testing</title>
</head>
<body>

<?php

//Log in token used to authenticate a user
$session_key = null;

//Session id for experiment
$session_id = null;


function loginTest($user,$pass){
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
    return json_decode($result,true);
}

function createSessionTest($exp){
    global $session_key;
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
        'name' => 'Automated Testing'.time(),
        'description' => 'Automated Testing Proc'.time(),
        'street' => '1 university ave',
        'city' => 'Lowell MA',
        'country' => 'USA'
     )); 

    //Run curl to get the response
    $result = curl_exec($ch);
    //Close curl
    curl_close($ch);
    //Parse the response to an associative array
    return json_decode($result,true);
}

function getSessionsTest($exp){
    //The target for this test
    //$target = "http://isensedev.cs.uml.edu/ws/api.php?method=createSession";
    $target = "localhost/ws/api.php?method=getSessions";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp,
     )); 

    //Run curl to get the response
    $result = curl_exec($ch);
    //Close curl
    curl_close($ch);
    //Parse the response to an associative array
    return json_decode($result,true);
}
//200 - OK
//400 - Bad Request
//600 - Bad Reqest/Doesn't Exist

//--------------------------------------------------------------------------------------------------------------------
//Login Test
//correct user/pass
echo "<b>Testing login with correct user/pass....</b><br>";
$login_response = loginTest('sor','sor');
if ($login_response['status'] == 200) {
    echo "SUCCESS, Successful login. UID: ";
    echo "<a href=\"http://localhost/profile.php?id=" . $login_response['data']['uid'] ."\">" . $login_response['data']['uid'] . "</a>";
    echo ", Session Key: " . $login_response['data']['session'] . '<br>';
    $session_key = $login_response['data']['session'];
} else {
    echo "FAILURE, Unsccessful login. JSON:";
    print_r($login_response);
    echo "<br>";
}
echo '<br>';
//incorrect user/pass
echo "<b>Testing login with incorrect user/pass....</b><br>";
$login_response = loginTest('sor','');
if ($login_response['status'] == 600) {
    echo "SUCCESS, Unsuccessful login.<br>";
} elseif ($login_response['status'] == 200) {
    echo "FAILURE, Successful login. JSON:";
    print_r($login_response);
    echo "<br>";
} else {
    echo "FAILURE, Something unexpected happened. JSON:";
    print_r($login_response);
    echo "<br>";
}
echo '<hr>';
//--------------------------------------------------------------------------------------------------------------------
//Create Session Test
//session on an open experiment
echo "<b>Trying to create a session on an open experiment....</b><br>";
$exp = 346;
$createSession_response = createSessionTest($exp);
if ($createSession_response['status'] == 200 ){
    $session_id = $createSession_response['data']['sessionId'];
    echo "SUCCESS, Successfuly created a session on an open experiment. ";
    echo "Exp: ";
    echo "<a href=\"http://localhost/experiment.php?id=" . $exp ."\">" . $exp . "</a>";
    echo ", SessionID: ";
    echo "<a href=\"http://localhost/newvis.php?sessions=" . $session_id  ."\">" . $session_id . "</a>";
    echo "<br>";
} else {
    echo "FAILURE, Could not create session on open experiment. JSON: ";
    print_r($createSession_response);
    echo "<br>";
}
echo "<br>";
//session on a closed experiment
echo "<b>Trying to create a session on a closed experiment....</b><br>";
$exp = 345;
$createSession_response = createSessionTest($exp);
if ($createSession_response['status'] == 400) {
    echo "SUCCESS, Unable to create a session on a closed experiment.<br>";
} elseif ($createSession_response['status'] == 200) {
    $session_id = $createSession_response['data']['sessionId'];
    echo "FAILURE, Created a session on a closed experiment. Exp: ";
    echo "<a href=\"http://localhost/experiment.php?id=" . $exp ."\">" . $exp . "</a>, SessionID: ";
    echo "<a href=\"http://localhost/newvis.php?sessions=" . $session_id  ."\">" . $session_id . "</a>.  JSON: ";
    print_r($createSession_response);
    echo "<br>";
} else {
    echo "FAILURE, Something unexpected happened. JSON:";
    print_r($login_response);
    echo "<br>";
}
echo "<hr>";
//--------------------------------------------------------------------------------------------------------------------
//Get Sessions Test

//This test verifies that we correctly got the session(s)
echo "<b>Tests that we correctly got the session(s)....</b><br>";
$exp = 346;
$getSessions_response = getSessionsTest($exp);
if ($getSessions_response['status'] == 200) {
    echo "SUCCESS, Sucessfully got session(s).<br>";
} else {
    echo "FAILURE, Unable to get session(s). JSON: ";
    print_r($getSessions_response);
    echo "<br>";
}
echo "<br>";

//This test verifies that we did not get the session(s).
echo "<b>Tests that we correctly got the session(s)....</b><br>";
$exp = 0;
$getSessions_response = getSessionsTest($exp);
if ($getSessions_response['status'] == 600) {
    echo "SUCCESS, Unable to session(s).<br>";
} elseif ($getSessions_response['status'] == 200) {
    echo "FAILURE, Sucessfully got session(s). JSON: ";
    print_r($getSessions_response);
    echo "<br>";
} else {
    echo "FAILURE, Something unexpected happened. JSON:";
    print_r($getSessions_response);
    echo "<br>";
}
echo "<hr>";




?>
</body>
</html>