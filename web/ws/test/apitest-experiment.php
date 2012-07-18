<?php
/*
$session_key= "500438cc6b240";


function uploadImageToExperimentTest($file, $exp){
    global $session_key;
    //The target for this test
    $target = "localhost/ws/api.php?method=uploadImageToExperiment";
    
    //Curl crap that will mostly stay the sameuploadim
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'image' => $file,
	'eid' => $exp,
	'img_name' => 'Automated Testing'.time(), 
	'img_desc' => 'Automated Testing Proc'.time(),
	'session_key' => $session_key
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);


}

//--------------------------------------------------------------------------------------------------------------------


//Upload Image to Experiment
echo "<h1>Upload Image to Experiment Test</h1>";


$file = safeString("/home/erin/Documents/iSENSE/web/ws/test/test.jpg");
//print_r($file);
$exp = 1;
$getUploadImagestoExperiment_response= uploadImageToExperimentTest($file, $exp);
echo $getUploadImagestoExperiment_response;
echo "<br>";

echo "<hr>";

*/
?>