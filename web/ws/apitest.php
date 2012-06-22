<html>
<head>
<title>iSenseDev Automated Testing</title>
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


//To do:
// getPeople
// getSessions (deal with limits)
// sessiondata
// getExperiments (make sure you deal with limits)
// getUserProfile
// getExperimentByUser
// getVisByUser
// getSessionsByUser
// getImagesByUser
// getVideosByUser
// addSessionData/updateSessionData
// getDataSince
// uploadImageToExperiment
// uploadImageToSession
// whatsMyIp --dont need to do
// getFileChecksum --dont need to do




require_once('../includes/config.php');

//Log in token used to authenticate a user
$session_key = null;

//Session id for experiment
$session_id = null;

function initialize(){
    global $db;
    echo "Setting experiment 1 to open.<br>";
    $result = $db->query('UPDATE experiments SET closed=0 WHERE experiment_id=1');

    if($result==1) {
        echo "Setting experiment 2 to closed.<br>";
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

//--------------------------------------------------------------------------------------------------------------------
//Initialize
echo "<h2><u>Initalization</u></h2>";
//setting up closed and open experiments
echo "<b>Initializing database....</b><br>";
if(initialize()){
	echo "Initialization completed!<br>";	
} else {
	echo "Initialization failed!<br>";
}
echo "<hr>";

//--------------------------------------------------------------------------------------------------------------------
//Login Test
echo "<h2><u>Login Test</u></h2>";
//Correct user/pass
echo "<b>Testing login with correct username and password....</b><br>";

$login_response = loginTest('james.dalphond@gmail.com','password');

if ($login_response['status'] == 200) {
    echo "SUCCESS, Successful login. UID: ";
    echo "<a href=\"http://localhost/profile.php?id=" . $login_response['data']['uid'] ."\">" . $login_response['data']['uid'] . "</a>";
    echo ", Session Key: " . $login_response['data']['session'] . '<br>';
    $session_key = $login_response['data']['session'];
} else {
    echo "FAILURE, Unsuccessful login. JSON:";
    print_r($login_response);
    echo "<br>";
}

echo '<br>';

//Incorrect user/pass
echo "<b>Testing login with incorrect username and password....</b><br>";

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
echo "<h2><u>Create Session Test</u></h2>";

//Session on an open experiment
echo "<b>Trying to create a session on an open experiment....</b><br>";

$exp = 1;
$createSession_response = createSessionTest($exp);

if ($createSession_response['status'] == 200 ){
    $session_id = $createSession_response['data']['sessionId'];
    echo "SUCCESS, Successfully created a session on an open experiment. ";
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


//Session on a closed experiment
echo "<b>Trying to create a session(s) on a closed experiment....</b><br>";

$exp = 2;
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
echo "<h2><u>Get Session Test</u></h2>";
//Verifies that we correctly got the session(s)
echo "<b>Tests that we correctly got the session(s)....</b><br>";

$exp = 1;
$getSessions_response = getSessionsTest($exp);

if ($getSessions_response['status'] == 200) {
    echo "SUCCESS, Successfully got session(s).<br>";
} else {
    echo "FAILURE, Unable to get session(s). JSON: ";
    print_r($getSessions_response);
    echo "<br>";
}


echo "<br>";


//Verifies that we did not get the session(s).
echo "<b>Tests that we did not get the session(s) in a non-existent experiment....</b><br>";

$exp = 0;
$getSessions_response = getSessionsTest($exp);

if ($getSessions_response['status'] == 600) {
    echo "SUCCESS, Unable to session(s).<br>";
} elseif ($getSessions_response['status'] == 200) {
    echo "FAILURE, Successfully got session(s). JSON: ";
    print_r($getSessions_response);
    echo "<br>";
} else {
    echo "FAILURE, Something unexpected happened. JSON:";
    print_r($getSessions_response);
    echo "<br>";
}
echo "More tests to come!";
echo "<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Fields Test
echo "<h2><u>Get Experient Fields Test</u></h2>";
//Verfies that the we got experiment fields
echo "<b>Tests that we got the experiment fields...</b><br>";

$exp = 1;
$getExperimentFields_response = getExperimentFieldsTest($exp);

if ($getExperimentFields_response['status'] == 200) {
	echo "SUCCESS, Got experiment fields.<br>";
} else {
	echo "FAILURE, Unable to get experiment fields. JSON: ";
	print_r($getExperimentFields_response);
	echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment fields
echo "<b>Tests that we did not get the experiment fields...</b><br>";

$exp = 0;
$getExperimentFields_response = getExperimentFieldsTest($exp);

if ($getExperimentFields_response['status'] == 600) {
	echo "SUCCESS, Unable to get experiment fields.<br>";
} elseif ($getExperimentFields_response['status'] == 200) {
	echo "FAILURE, Got experiment fields. JSON: ";
	print_r($getExperimentFields_response);
	echo"<br>";
} else {
	echo "FAILURE, Something unexpected happened. JSON:";
    print_r($getExperimentFields_response);
	echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Visualizations Test
echo "<h2><u>Create Session Test</u></h2>";
//Verifies that we got the experiment visualizations
echo "<b>Tests that we got the experiment visualizations...</b><br>";

$exp = 346;
$getExperimentVisualizations_response = getExperimentVisualizationsTest($exp);

if ($getExperimentVisualizations_response['status'] == 200) {
	echo "SUCCESS, Got experiment visualizations.<br>";
} else {
	echo "FAILURE, Unable to get experiment visualizations. JSON: ";
	print_r($getExperimentVisualizations_response);
	echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment fields
echo "<b>Tests that we did not get the experiment visualizations...</b><br>";

$exp = 0;
$getExperimentVisualizations_response = getExperimentVisualizationsTest($exp);

if ($getExperimentVisualizations_response['status'] == 600) {
	echo "SUCCESS, Unable to get experiment visualizations.<br>";
} elseif ($getExperimentVisualizations_response['status'] == 200) {
	echo "FAILURE, Got experiment fields. JSON: ";
	print_r($getExperimentVisualizations_response);
	echo"<br>";
} else {
	echo "FAILURE, Something unexpected happened. JSON:";
    print_r($getExperimentVisualizations_response);
	echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Tags Test
echo "<h2><u>Get Experiment Tags Test</u></h2>";
//Verifies that we got the experiment tags
echo "<b>Tests that we got the experiment tags...</b><br>";

$exp = 1;
$getExperimentTags_response = getExperimentTagsTest($exp);

if ($getExperimentTags_response['status'] == 200) {
	echo "SUCCESS, Got experiment tags.<br>";
} else {
	echo "FAILURE, Unable to get experiment tags. JSON: ";
	print_r($getExperimentTags_response);
	echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment tags
echo "<b>Tests that we did not get the experiment tags...</b><br>";

$exp = 0;
$getExperimentTags_response = getExperimentTagsTest($exp);

if ($getExperimentTags_response['status'] == 600) {
	echo "SUCCESS, Unable to get experiment tags.<br>";
} elseif ($getExperimentTags_response['status'] == 200) {
	echo "FAILURE, Got experiment tags. JSON: ";
	print_r($getExperimentTags_response);
	echo"<br>";
} else {
	echo "FAILURE, Something unexpected happened. JSON:";
    print_r($getExperimentTags_response);
	echo"<br>";
}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Videos Test
echo "<h2><u>Get Experiment Videos Test</u></h2>";
//Verifies that we got the experiment videos
echo "<b>Tests that we got the experiment videos...</b><br>";

$exp = 183;
$getExperimentVideos_response = getExperimentVideosTest($exp);

if ($getExperimentVideos_response['status'] == 200) {
	echo "SUCCESS, Got experiment videos.<br>";
} else {
	echo "FAILURE, Unable to get experiment videos. JSON: ";
	print_r($getExperimentVideos_response);
	echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment videos
echo "<b>Tests that we did not get the experiment videos...</b><br>";

$exp = 0;
$getExperimentVideos_response = getExperimentVideosTest($exp);

if ($getExperimentVideos_response['status'] == 600) {
	echo "SUCCESS, Unable to get experiment videos.<br>";
	} elseif ($getExperimentVideos_response['status'] == 200) {
		echo "FAILURE, Got experiment videos. JSON: ";
		print_r($getExperimentVideos_response);
		echo"<br>";
	} else {
		echo "FAILURE, Something unexpected happened. JSON:";
		print_r($getExperimentVideos_response);
		echo"<br>";
	}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------
//Get Experiment Images Test
echo "<h2><u>Get Experiment Images Test</u></h2>";
//Verifies that we got the experiment images
echo "<b>Tests that we got the experiment images...</b><br>";

$exp = 183;
$getExperimentImages_response = getExperimentImagesTest($exp);

if ($getExperimentImages_response['status'] == 200) {
	echo "SUCCESS, Got experiment images.<br>";
} else {
	echo "FAILURE, Unable to get experiment images. JSON: ";
	print_r($getExperimentImages_response);
	echo"<br>";
}


echo "<br>";


//Verifies that we did not get the experiment images
echo "<b>Tests that we did not get the experiment images...</b><br>";

$exp = 346;
$getExperimentImages_response = getExperimentImagesTest($exp);

if ($getExperimentImages_response['status'] == 600) {
	echo "SUCCESS, Unable to get experiment images.<br>";
	} elseif ($getExperimentImages_response['status'] == 200) {
		echo "FAILURE, Got experiment images. JSON: ";
		print_r($getExperimentImages_response);
		echo"<br>";
	} else {
		echo "FAILURE, Something unexpected happened. JSON:";
		print_r($getExperimentImages_response);
		echo"<br>";
	}

echo"<hr>";



//--------------------------------------------------------------------------------------------------------------------


?>
</body>
</html>