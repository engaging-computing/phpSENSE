<?php

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


//--------------------------------------------------------------------------------------------------------------------

//Get Experiment Fields Test
echo "<h1>Get Experiment Fields Test</h1>";

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

$exp = -1;
$getExperimentTags_response = getExperimentTagsTest($exp);

if ($getExperimentTags_response['status'] == 600) {
    echo "<div class='success'>SUCCESS</div>, Unable to get experiment tags.<br>";
    print_r($getExperimentTags_response);
    echo "<br>";
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

//Get Experiment Videos Test
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

?>
