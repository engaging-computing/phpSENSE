<html>
<head>
<title>iSenseDev Automated Testing</title>
<link rel="stylesheet" type="text/css" href="apitest.css" />
</head>
<body>

<?php

//Changes that I made:
// Cleaned up code - spacing, spelling, experiment numbers (I tried to stick with using experiments 0, 1, and 2 for the most part- see NOTES)
// Jim added the initialization function - if you don't  know what it does, you might want to ask him to explain
// Add testing for getExperimentFields, getExperimentVisualizations, getExperimentTags, getExperimentVideos, and getExperimentImages





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
//kc - low - getPeople
//*** - getSessions (deal with limits)
//*** - getExperiments (make sure you deal with limits)
//kc - low - getUserProfile
//kc - low - getExperimentByUser
//ar - low - getVisByUser
//ar - low - getSessionsByUser
//ar - low - getImagesByUser
//low - getVideosByUser
//jeremy email - addSessionData/updateSessionData
//skip for now - getDataSince
// uploadImageToExperiment
//jermey uses this one - uploadImageToSession


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
        'experiment' => $exp,
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
        'experiment' => $exp,
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
        'experiment' => $exp,
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
        'experiment' => $exp,
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
        'experiment' => $exp,
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
        'experiment' => $exp,
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
/*
function getUserProfileTest($user){
    //The target for this test
    $target =  "localhost/ws/api.php?method=getUserProfileTest";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' = $user;
        ));
        
        //Run curl to get the response
        $result = curl_exec($ch);
        
        //Close curl
        curl_close($ch);
        
        //Parse the response to an associative array
        return json_decode($result,true);
    
}
*/

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
        'user' => $id,
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        //echo "<br>".$result."<br>";
        return json_decode($result,true);
}

function getVisByUserTest($user){
    //The target for this test
    $target = "localhost/ws/api.php?method=getVisByUser";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'user' => $user,
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
echo "<h1>Initalization</h1>";
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

$login_response = loginTest('james.dalphond@gmail.com','password');

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


//Verifies that we did not get the experiment fields
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
//Get User Profile Test

// Correctly get the user's profile



//--------------------------------------------------------------------------------------------------------------------
// Get Experiment by User Test

// Get experiment from a user that has experiment
echo "<h1>Get Experiment by User Test</h1>";
echo "<h2>Tests that we got an experiment from a user with experiments...</h2>";

$id = 5;
$getExperimentByUser_response = getExperimentByUserTest($id);

if ($getExperimentByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Got experiment from user.<br>";
} elseif ($getExperimentByUser_response['status'] == 200) {
    echo "<div class='failure'>FAILURE</div>, Did not get experiment from user. JSON: ";
    print_r($getExperimentByUser_response);
    echo"<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Something unexpected happened. JSON:";
    print_r($getExperimentByUser_response);
    echo"<br>";
}

echo"<hr>";

//--------------------------------------------------------------------------------------------------------------------
//Get Visualizations by User
echo "<h1>Get Visualizations by User</h1>";

echo "<h2>Tests that we can get visualizations by user...</h2>";

$user = 5;
$getVisByUser_response = getVisByUserTest($user);
if ($getVisByUser_response['status'] == 200) {
    echo "<div class='success'>SUCCESS</div>, Able to get visualizations.<br>";
} else {
    echo "<div class='failure'>FAILURE</div>, Unable to get visualizations. JSON: ";
    print_r($getVisByUser_response);
    echo "<br>";
}

echo "<hr>";

?>
</body>
</html>