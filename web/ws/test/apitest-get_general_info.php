<?php


// Skipped getDataSince for now

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


        'action' => 'browse',
        'type' => 'people',
        'query' => '',
        'page' => '1',
        'limit' => '10', 
        'sort' => 'default'
        )); 

    //Run curl to get the response
    $result = curl_exec($ch);
    //Close curl
    curl_close($ch);
    //Parse the response to an associative array
    echo "<br>".$result."<br>";
    return json_decode($result,true);
}

function getExperimentsTest($page, $limit, $sort){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperiments";

    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'page' => $page,
        'limit' => $limit,
        'query' => '',
        'sort' => $sort,
        'action' => 'browse',
        'type' => 'experiments'
        )); 

    //Run curl to get the response
    $result = curl_exec($ch);
    //Close curl
    curl_close($ch);
    //Parse the response to an associative array
    return json_decode($result,true);
}

function getVisualizationsTest($query)){
    
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


        'action' => 'browse',
        'type' => 'people',
        'query' => '',
        'page' => '1',
        'limit' => '10', 
        'sort' => 'default'
        )); 

    //Run curl to get the response
    $result = curl_exec($ch);
    //Close curl
    curl_close($ch);
    //Parse the response to an associative array
    echo "<br>".$result."<br>";
    return json_decode($result,true);
}

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
//Get Experiments Test

echo "<h1>Get Experiments Test</h2>";

//Test that we got the correct page
$page=3;








//--------------------------------------------------------------------------------------------------------------------
/*
//Get People Test

//Finds the person that you have searched for
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




?>