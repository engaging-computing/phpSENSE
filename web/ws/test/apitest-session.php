<?php

require_once('apitest-login.php') ;

function createSessionTest($params){
    global $session_key;

    //The target for this test
    $target = "localhost/ws/api.php?method=createSession";
   
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params
    );
       
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

function putSessionDataTest($params){   
    global $session_key;

    //The target for this test
    $target = "localhost/ws/api.php?method=putSessionData";
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params
    );
       
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

//--------------------------------------------------------------------------------------------------------------------

//Create Session Test

echo "<h1>Create Session Test</h1>";


//Session on a closed experiment
echo "<h2>Trying to create a session(s) on a closed experiment....</h2>";

$params = array('session_key' => $session_key, 'eid'=> 2, 'name' => 'Automated Testing Proc'.time(), 'description' => 'Automated Testing Proc'.time(), 'street' => '1 university ave', 'city' => 'Lowell MA', 'country' => 'USA');
$createSession_response = createSessionTest($params);


if ($createSession_response['status'] == 400) {
    echo "<div class='success'>SUCCESS</div>, Unable to create a session on a closed experiment.<br>";
} elseif ($createSession_response['status'] == 200) {
    $session_id = $createSession_response['data']['sessionId'];
    echo "<div class='failure'>FAILURE</div>, Created a session on a closed experiment. Exp: ";
    echo "<a href=\"http://localhost/experiment.php?id=" . $exp ."\">" . $exp . "</a>, SessionID: ";
    echo "<a href=\"http://localhost/newvis.php?sessions=" . $session_id  ."\">" . $session_id . "</a>.  JSON: ";
    print_r($createSession_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($login_response);
    echo "<br>";
}


/*
echo "<br>";

//Session on an account which is not logged in
echo "<h2>Trying to create a session(s) within an account that is not logged in....</h2>";

$params = array('session_key' => $session_key, 'eid'=> 4, 'name' => 'Automated Testing Proc'.time(), 'description' => 'Automated Testing Proc'.time(), 'street' => '1 university ave', 'city' => 'Lowell MA', 'country' => 'USA');
$createSession_response = createSessionTest($params);

if ($createSession_response['status'] == 400 ){
    $session_id = $createSession_response['data']['sessionId'];
    echo "<div class='success'>SUCCESS</div>, Successfully created a session on an open experiment. ";
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Could not create session on open experiment. JSON: ";
    print_r($createSession_response);
    echo "<br>";
}
*/

echo "<br>";

//Session without an experiment id
echo "<h2>Trying to create a session(s) without an experiment id....</h2>";

$params = array('session_key' => $session_key, 'eid' => -1, 'name' => 'Automated Testing Proc'.time(), 'description' => 'Automated Testing Proc'.time(), 'street' => '1 university ave', 'city' => 'Lowell MA', 'country' => 'USA');
$createSession_response = createSessionTest($params);

if ($createSession_response['status'] == 400){
    $session_id = $createSession_response['data']['sessionId'];
    echo "<div class='success'>SUCCESS</div>, ";
    echo $createSession_response['data']['msg'];
    echo "<br><br>";
} else {
    echo "<div class='failure'>FAILURE</div>, ";
    echo $createSession_response['data']['msg'];
    print_r($createSession_response);
    echo "<br><br>";
}

//Session on an open experiment
echo "<h2>Trying to create a session on an open experiment....</h2>";

$params = array('session_key' => $session_key, 'eid'=> 4, 'name' => 'Automated Testing Proc'.time(), 'description' => 'Automated Testing Proc'.time(), 'street' => '1 university ave', 'city' => 'Lowell MA', 'country' => 'USA');
$createSession_response = createSessionTest($params);

if ($createSession_response['status'] == 200 ){
    $session_id = $createSession_response['data']['sessionId'];
    echo "<div class='success'>SUCCESS</div>, Successfully created a session on an open experiment. ";
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Could not create session on open experiment. JSON: ";
    print_r($createSession_response);
    echo "<br>";
}

print_r ($createSession_response);


echo "<br>";


echo "<hr>";

//--------------------------------------------------------------------------------------------------------------------
/*
//Upload Image To Session Test
echo "<h1>Upload Image To Session Test</h1>";

echo "<br>";

echo "<hr>";
*/
//--------------------------------------------------------------------------------------------------------------------

//Put Session Data Test
echo "<h1>Put Session Data Test</h1>";

//Verifies that we successfully put the session data
echo "<h2>Tests that we successfully put the session data...</h2>";

$params = array('eid'=> 4, 'data' => "[[\"90\", \"40\"]]", 'sid' => $session_id, 'session_key' => $session_key);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 200){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 400){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 550){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 551){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}

echo "<br>";

//Verifies that we could not log in due to invalid session key
echo "<h2>Verifies that we could not log in due to invalid session key...</h2>";

$params = array('eid'=> 4, 'data' => "[[\"90\", \"40\"]]", 'sid' => $session_id, 'session_key' => 1);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 400){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 200){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 550){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 551){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}

echo "<br>";

//Verifies that we have malformed data
echo "<h2>Verifies that we have malformed data...</h2>";

$data = "((\"def\",\"abc\"]]";
$params = array('eid'=> 4, 'data' => $data, 'sid' => $session_id, 'session_key' => $session_key);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 550){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 400){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 200){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}elseif($putSessionData_response['status'] == 551){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
    echo "<br>";
}

echo "<br>";

//Verifies that we are missing data
echo "<h2>Verifies that we are missing data...</h2>";

$params = array('eid'=> 4, 'sid' => $session_id, 'session_key' => $session_key);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 551){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 400){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 200){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 550){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}

echo "<br><br>";

//Verifies that we are missing experiment id
echo "<h2>Verifies that we are missing experiment id...</h2>";

$params = array('data' => "[[\"90\", \"40\"]]", 'sid' => $session_id, 'session_key' => $session_key);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 551){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 400){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 200){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 550){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}

echo "<br><br>";

//Verifies that we are missing session key
echo "<h2>Verifies that we are missing session key...</h2>";

$params = array('eid'=> 4, 'data' => "[[\"90\", \"40\"]]", 'sid' => $session_id);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 551){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 400){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 200){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 550){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}

echo "<br><br>";

//Verifies that we are missing session id
echo "<h2>Verifies that we are missing session id...</h2>";

$params = array('eid'=> 4, 'data' => "[[\"90\", \"40\"]]", 'session_key' => $session_key);
$putSessionData_response = putSessionDataTest($params);

if($putSessionData_response['status'] == 551){
    echo "<div class='success'>SUCCESS</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 400){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 200){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}elseif($putSessionData_response['status'] == 550){
    echo "<div class ='failure'>FAILURE</div>, ";
    echo $putSessionData_response['data']['msg'];
}

echo "<br>";

echo "<hr>";


?>