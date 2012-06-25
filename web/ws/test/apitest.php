 <html>
<head>
<title>iSenseDev Automated Testing</title>
<link rel="stylesheet" type="text/css" href="apitest.css" />
</head>
<body>

<?php

//HTTP codes
// 200 - OK
// 400 - Bad Request
// 600 - Bad Reqest/Doesn't Exist

//Experiments:
// 0 has no data
// 1 is an open experiment
// 2 is a closed experiment
// 3 is a deleted experimetnt
// 346 has data

//Users:
// james.dalphond@gmail.com (password) - uid: 5


//To do:
//*** - getPeople
//*** - getSessions (deal with limits)
//*** - getExperiments (make sure you deal with limits)
//DONE! kc - low - getUserProfile
//DONE! kc - low - getExperimentByUser
//DONE! ar - low - getVisByUser
//DONE! ar - low - getSessionsByUser
//DONE! ar - low - getImagesByUser
//DONE! low - getVideosByUser
//jeremy email - putSessionData/updateSessionData
// uploadImageToExperiment
//jermey uses this one - uploadImageToSession
//skip for now - getDataSince

// apitest-login.php
// login

// apitest-get_general_info.php
// getExperiments
// getPeople
// getVisualizations
// getSessions
// getDataSince

// apitest-get_experiment_info.php
// getExperimentFields
// getExperimentVisualizations
// getExperimentTags
// getExperimentVideos
// getExperimentImages

// apitest-get_user_info.php
// getUserProfile
// getExperimentByUser
// getVisByUser
// getSessionsByUser
// getImagesByUser
// getVideosByUser

// apitest-session.php
// createSession
// uploadImageToSession
// putSessionData


// apitest-experiment.php
// uploadImageToExperiment


//Need to fix mysql error - user -1 has images


require_once('../includes/config.php');

//Log in token used to authenticate a user
$session_key = null;

//Session id for experiment
$session_id = null;

//User id for logged in user
$uid = null;

function initialize(){
    global $db;
    echo "Setting experiment 1 to open...<br>";
    $result = $db->query('UPDATE experiments SET closed=0 WHERE experiment_id=1');
    
    if($result==1) {
        echo "Setting experiment 2 to closed...<br>";
        $result2 = $db->query('UPDATE experiments SET closed=1 WHERE experiment_id=2');
        if($result2 == 1){
            return true;
        }
    } else {
        return false;
    }
}

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

function createSessionTest($exp){
    global $session_key;
    
    //The target for this test
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
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

function getSessionsTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getSessions";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);
}

function getExperimentFieldsTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperimentFields";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);
} 

function getExperimentVisualizationsTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperimentVisualizations";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);	
}

function getExperimentTagsTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperimentTags";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);	
}

function getExperimentVideosTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperimentVideos";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);	
}

function getExperimentImagesTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperimentImages";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);	
}
/*
function getPeopleTest($query)){
    
    //The target for this test
    $target = "localhost/ws/api.php?method=getPeople";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'action' => 'search',
        'type' => 'people',
        'query' => $query,
        'page' => 1,
        'limit' => 10, 
        'sort' => 'default'
        
        
        /* 'action' => 'browse',
        'type' => 'people',
        'query' => '',
        'page' => '1',
        'limit' => '10', 
        'sort' => 'default'
        */
//       )); 
        
        //Run curl to get the response
//        $result = curl_exec($ch);
        //Close curl
//        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
//        return json_decode($result,true);
//}

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


//--------------------------------------------------------------------------------------------------------------------
//Initialize
echo "<h1>Initialization</h1>";
//setting up closed and open experiments
echo "<h2>Initializing database....</h2>";
if(initialize()){
    echo "<div class='success'>SUCCESS</div>, Initialization completed!<br>";	
} else {
    echo "<div class='failure'>FAILURE</div>, Initialization failed!<br>";
}
echo "<hr>";

//--------------------------------------------------------------------------------------------------------------------
//Login Test
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
} else {
    echo "<div class='failure'>FAILURE</div>, Unsuccessful login. JSON: ";
    print_r($login_response);
    echo "<br>";
}

echo '<br>';

//Incorrect user/pass
echo "<h2>Testing login with incorrect username and password....</h2>";

$login_response = loginTest('sor','');

if ($login_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unsuccessful login.<br>";
} elseif ($login_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Successful login. JSON: ";
    print_r($login_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($login_response);
    echo "<br>";
}

echo '<hr>';



//--------------------------------------------------------------------------------------------------------------------
//Create Session Test
echo "<h1>Create Session Test</h1>";

//Session on an open experiment
echo "<h2>Trying to create a session on an open experiment....</h2>";

$exp = 1;
$createSession_response = createSessionTest($exp);

if ($createSession_response['status'] == 200 ){
    $session_id = $createSession_response['data']['sessionId'];
    echo "<div class='success'>SUCCESS</div>, Successfully created a session on an open experiment. ";
    echo "Exp: ";
    echo "<a href=\"http://localhost/experiment.php?id=" . $exp ."\">" . $exp . "</a>";
    echo ", SessionID: ";
    echo "<a href=\"http://localhost/newvis.php?sessions=" . $session_id  ."\">" . $session_id . "</a>";
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Could not create session on open experiment. JSON: ";
    print_r($createSession_response);
    echo "<br>";
}


echo "<br>";


//Session on a closed experiment
echo "<h2>Trying to create a session(s) on a closed experiment....</h2>";

$exp = 2;
$createSession_response = createSessionTest($exp);

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

echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Sessions Test
echo "<h1>Get Session Test</h1>";
//Verifies that we correctly got the session(s)
echo "<h2>Tests that we correctly got the session(s)....</h2>";

$exp = 1;
$getSessions_response = getSessionsTest($exp);

if ($getSessions_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Successfully got session(s).<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get session(s). JSON: ";
    print_r($getSessions_response);
    echo "<br>";
}


echo "<br>";


//Verifies that we did not get the session(s).
echo "<h2>Tests that we did not get the session(s) in a non-existent experiment....</h2>";

$exp = 0;
$getSessions_response = getSessionsTest($exp);

if ($getSessions_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to session(s).<br>";
} elseif ($getSessions_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Successfully got session(s). JSON: ";
    print_r($getSessions_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON:";
    print_r($getSessions_response);
    echo "<br>";
}
echo "More tests to come!";
echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Fields Test
echo "<h1>Get Experient Fields Test</h1>";
//Verfies that the we got experiment fields
echo "<h2>Tests that we got the experiment fields...</h2>";

$exp = 1;
$getExperimentFields_response = getExperimentFieldsTest($exp);

if ($getExperimentFields_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment fields.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get experiment fields. JSON: ";
    print_r($getExperimentFields_response);
    echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment fields
echo "<h2>Tests that we did not get the experiment fields...</h2>";

$exp = 0;
$getExperimentFields_response = getExperimentFieldsTest($exp);

if ($getExperimentFields_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment fields.<br>";
} elseif ($getExperimentFields_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got experiment fields. JSON: ";
    print_r($getExperimentFields_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getExperimentFields_response);
    echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Visualizations Test
echo "<h1>Create Session Test</h1>";
//Verifies that we got the experiment visualizations
echo "<h2>Tests that we got the experiment visualizations...</h2>";

$exp = 346;
$getExperimentVisualizations_response = getExperimentVisualizationsTest($exp);

if ($getExperimentVisualizations_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment visualizations.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get experiment visualizations. JSON: ";
    print_r($getExperimentVisualizations_response);
    echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment visualizations
echo "<h2>Tests that we did not get the experiment visualizations...</h2>";

$exp = 0;
$getExperimentVisualizations_response = getExperimentVisualizationsTest($exp);

if ($getExperimentVisualizations_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment visualizations.<br>";
} elseif ($getExperimentVisualizations_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got experiment fields. JSON: ";
    print_r($getExperimentVisualizations_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getExperimentVisualizations_response);
    echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Tags Test
echo "<h1>Get Experiment Tags Test</h1>";
//Verifies that we got the experiment tags
echo "<h2>Tests that we got the experiment tags...</h2>";

$exp = 1;
$getExperimentTags_response = getExperimentTagsTest($exp);

if ($getExperimentTags_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment tags.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get experiment tags. JSON: ";
    print_r($getExperimentTags_response);
    echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment tags
echo "<h2>Tests that we did not get the experiment tags...</h2>";

$exp = 0;
$getExperimentTags_response = getExperimentTagsTest($exp);

if ($getExperimentTags_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment tags.<br>";
} elseif ($getExperimentTags_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got experiment tags. JSON: ";
    print_r($getExperimentTags_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getExperimentTags_response);
    echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Videos Test;

echo "<h1>Get Experiment Videos Test</h1>";
//Verifies that we got the experiment videos
echo "<h2>Tests that we got the experiment videos...</h2>";

$exp = 183;
$getExperimentVideos_response = getExperimentVideosTest($exp);

if ($getExperimentVideos_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment videos.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get experiment videos. JSON: ";
    print_r($getExperimentVideos_response);
    echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment videos
echo "<h2>Tests that we did not get the experiment videos...</h2>";

$exp = 0;
$getExperimentVideos_response = getExperimentVideosTest($exp);

if ($getExperimentVideos_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment videos.<br>";
} elseif ($getExperimentVideos_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got experiment videos. JSON: ";
    print_r($getExperimentVideos_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getExperimentVideos_response);
    echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Images Test
echo "<h1>Get Experiment Images Test</h1>";
//Verifies that we got the experiment images
echo "<h2>Tests that we got the experiment images...</h2>";

$exp = 183;
$getExperimentImages_response = getExperimentImagesTest($exp);

if ($getExperimentImages_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment images.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get experiment images. JSON: ";
    print_r($getExperimentImages_response);
    echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment images
echo "<h2>Tests that we did not get the experiment images...</h2>";

$exp = 346;
$getExperimentImages_response = getExperimentImagesTest($exp);

if ($getExperimentImages_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment images.<br>";
} elseif ($getExperimentImages_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got experiment images. JSON: ";
    print_r($getExperimentImages_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getExperimentImages_response);
    echo "<br>";
}

echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get People Test

//Finds the person that you have searched for
/*
$action = 'search';
$query = 'Non Admin';
$getPeople_response = getPeopleTest($action, $query);

if ($getPeople_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Found person.<br>";
} elseif ($getPeople_response['status'] == 600) {
    echo "<div class='failure'>FAILURE</div>, Did not find person. JSON: ";
    print_r($getPeople_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON:";
    print_r($getPeople_response);
    echo"<br>";
}


*/



//--------------------------------------------------------------------------------------------------------------------
// Get Experiment by User Test
// Get experiment(s) from a user that has experiment(s)
echo "<h1>Get Experiment by User Test</h1>";
echo "<h2>Tests that we got an experiment(s) from a user with experiment(s)...</h2>";

$id = 5;
$getExperimentByUser_response = getExperimentByUserTest($id);

if ($getExperimentByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment from user.<br>";
} elseif ($getExperimentByUser_response['status'] == 600) {
    echo "<div class='failure'>FAILURE</div>, Did not get experiment from user. JSON: ";
    print_r($getExperimentByUser_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON:";
    print_r($getExperimentByUser_response);
    echo"<br>";
}

echo"<br>";

//Verifies that we did not get the experiment(s) from a user without experiment(s)
echo "<h2>Tests that we did not get the experiment(s) from a user without experiments...</h2>";

$id = 95;
$getExperimentByUser_response = getExperimentByUserTest($id);

if ($getExperimentByUser_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment(s) from user.<br>";
} elseif ($getExperimentByUser_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got experiment(s) from user. JSON: ";
    print_r($getExperimentByUser_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getExperimentByUser_response);
    echo "<br>";
}

echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Vis by User
echo "<h1>Get Vis by User</h1>";

//Verfies we got Vis by user
echo "<h2>Tests that we can get visualizations by user...</h2>";

$id = 5;
$getVisByUser_response = getVisByUserTest($id);

if ($getVisByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Able to get visualizations.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get visualizations. JSON: ";
    print_r($getVisByUser_response);
    echo "<br>";
}

echo "<br>";

//Verifies that we failed getting Vis by user
echo "<h2>Tests that we cannot get visualizations by user...</h2>";

$id = -1;
$getVisByUser_response = getVisByUserTest($id);

if ($getVisByUser_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get vis from user.<br>";
} elseif ($getVisByUser_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got vis from user. JSON: ";
    print_r($getVisByUser_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getVisByUser_response);
    echo "<br>";
}

echo "<hr>";

//--------------------------------------------------------------------------------------------------------------------

//Get Images by User
echo "<h1>Get Images by User</h1>";

//Verfies we got Images by user
echo "<h2>Tests that we can get images by user...</h2>";

$id = 1;
$getImagesByUser_response = getImagesByUserTest($id);

if ($getImagesByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Able to get images.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get images. JSON: ";
    print_r($getImagesByUser_response);
    echo "<br>";
}

echo "<br>";

//Verifies that we failed getting Images by user
echo "<h2>Tests that we cannot get images by user...</h2>";

$id = -1;
$getImagesByUser_response = getImagesByUserTest($id);

if ($getImagesByUser_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get images from user.<br>";
} elseif ($getImagesByUser_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got images from user. JSON: ";
    print_r($getImagesByUser_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getImagesByUser_response);
    echo "<br>";
}

echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
// Get Videos by User Test

// Verifies that we got video(s) from a user that has experiment with videos
echo "<h1>Get Videos by User Test</h1>";
echo "<h2>Tests that we got a video from a user with videos...</h2>";

$id = 3;
$getVideosByUser_response = getVideosByUserTest($id);

if ($getVideosByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got video(s) from user.<br>";
} elseif ($getVideosByUser_response['status'] == 600) {
    echo "<div class='failure'>FAILURE</div>, Did not get video(s) from user. JSON: ";
    print_r($getVideosByUser_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON:";
    print_r($getVideosByUser_response);
    echo"<br>";
}

echo"<br>";

//Verifies that we did not get the video(s) from the user that does not have video(s)
echo "<h2>Tests that we did not get the videos from a user without videos...</h2>";

$id = 95;
$getVideosByUser_response = getVideosByUserTest($id);

if ($getVideosByUser_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get video(s) from user.<br>";
} elseif ($getVideosByUser_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got video(s) from user. JSON: ";
    print_r($getExperimentByUser_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getVideosByUser_response);
    echo "<br>";
}

echo "<hr>";




//--------------------------------------------------------------------------------------------------------------------
//Get User Profile Test

//Verifies that we have received a user profile
echo "<h1>Gets User Profile Test</h1>";
echo "<h2>Tests that we got a user profile..</h2>";

$id = 1;
$getUserProfile_response = getUserProfileTest($id);

if ($getUserProfile_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got user profile.<br>";
} elseif ($getUserProfile_response['status'] == 600) {
    echo "<div class='failure'>FAILURE</div>, Did not get user profile. JSON: ";
    print_r($getUserProfile_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON:";
    print_r($getUserProfile_response);
    echo"<br>";
}

echo"<br>";

//Verifies that we did not get user profile
echo "<h2>Tests that we did not get the user profile...</h2>";

$id = -1;
$getUserProfile_response = getUserProfileTest($id);

if ($getUserProfile_response.length == 0) {
    echo "<div class='success'>SUCCESS</div>, Unable to get user profile.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, You got the user profile. JSON: ";
    print_r($getUserProfile_response);
    echo "<br>";
}

echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Sessions By User Test

echo "<h1>Get Sessions by User</h1>";


//Verifies that we got sessions by user
echo "<h2>Tests that we can get sessions by user...</h2>";

$id = 319;
$getSessionsByUser_response = getSessionsByUserTest($id);

if ($getSessionsByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Able to get sessions.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get sessions. JSON: ";
    print_r($getSessionsByUser_response);
    echo "<br>";
}

echo "<br>";

//Verifies that we failed getting Sessions by user
echo "<h2>Tests that we cannot get sessions by user...</h2>";

$id = -1;
$getSessionsByUser_response = getSessionsByUserTest($id);

if ($getSessionsByUser_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get sessions from user.<br>";
} elseif ($getSessionsByUser_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Got sessions from user. JSON: ";
    print_r($getSessionsByUser_response);
    echo "<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON: ";
    print_r($getSessionsByUser_response);
    echo "<br>";
}

echo "<hr>";



?>
</body>
</html>