<?php

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

// Get Experiment by User Test
echo "<h1>Get Experiment by User Test</h1>";

// Get experiment(s) from a user that has experiment(s)
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
echo "<h1>Get Videos by User Test</h1>";

// Verifies that we got video(s) from a user that has experiment with videos
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
echo "<h1>Gets User Profile Test</h1>";

//Verifies that we have received a user profile
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