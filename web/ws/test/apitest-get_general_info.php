<?php

function getExperimentTest($exp){
    //The target for this test
    $target = "localhost/ws/api.php?method=getExperiment";
    
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

function getExperimentsTest($page, $limits, $query, $sort, $action){
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
	'limit' => $limits,
	'query' => $query,
	'sort' => $sort,
	'action' => $action,
	'type' => 'experiments'
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
	
        return json_decode($result,true);
}

function getPeopleTest($page, $limits, $query, $sort, $action){
    //The target for this test
    $target = "localhost/ws/api.php?method=getPeople";
    
    //Curl crap that will mostly stay the same
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'page' => $page,
	'limits' => $limits,
	'query' => $query,
	'sort' => $sort,
	'action' => $action,
	'type' => 'people'
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
        'experiment' => $exp
        )); 
        
        //Run curl to get the response
        $result = curl_exec($ch);
        //Close curl
        curl_close($ch);
        //Parse the response to an associative array
        return json_decode($result,true);
}

//skipped getDataSince for now


//--------------------------------------------------------------------------------------------------------------------

//Get Experiment Test
echo "<h1>Get Experiment Test</h1>";

//Verifies that the experiment is correct
echo "<h2>Verifies that experiment is correct...</h2>";

$exp = 1;

$getExperiment_response = getExperimentTest($exp);

  if($getExperiment_response['data']['experiment_id'] == $exp){
      echo "<div class='success'>SUCCESS</div>, The experiments ids matched.<br>";
  } else{
      echo "<div class='failure'>FAILURE</div>, The experiment ids did not match.<br>";
  }

echo "<br>";


//--------------------------------------------------------------------------------------------------------------------

//Get Experiments Test
echo "<h1>Get Experiments Test</h1>";

//Verifies that the first page has different experiments to the ones on the following page
echo "<h2>Verifies that pages are working....</h2>";

$page = 1;
$limits = 4;
$query = "";
$sort = "default";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);
$result1= Array();

$i=0;
foreach($getExperiments_response['data'] as $tmp){
  $result1[$i] = $tmp['meta']['experiment_id']; 
  $i++;
}

$page = 2;
$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

foreach($getExperiments_response['data'] as $tmp){
  if(in_array($tmp,$result1 )) {
      $successflag = 0;
  }else{
      $successflag = 1;
      break;
  }
}

  if($successflag == 0){
      echo "<div class='failure'>FAILURE</div>, Page 1 and 2 are not different.<br>";
  } elseif($successflag == 1){
      echo "<div class='success'>SUCCESS</div>, Page 1 and 2 are different.<br>";
  }

echo "<br>";

//Verifies that the number of experiments on a page does not exceed its limit
echo "<h2>Verifies that limits are working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "default";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

  if(count($getExperiments_response['data']) > $limits){
      echo "<div class='failure'>FAILURE</div>, Page 1 does not satisfy the limit requirement.<br>";
  } else{
      echo "<div class='success'>SUCCESS</div>, Page 1 satisfies the limit requirement.<br>";
  }

$page = 2;
$limits = 10;
$query = "";
$sort = "default";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

$result1=Array();

  if(count($getExperiments_response['data'])  > $limits){
      echo "<div class='failure'>FAILURE</div>, Page 2 does not satisfy the limit requirement.<br>";
  } else{
      echo "<div class='success'>SUCCESS</div>, Page 2 satisfies the limit requirement.<br>";
  }

echo "<br>";

//Verifies that the Rating sorting is working correctly
echo "<h2>Verifies that Rating sorting is working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "rating";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

for($j=0; $j<$limits; $j++){
  $newest = $getExperiments_response['data'][$j]['meta']['rating'];
  $next =  $getExperiments_response['data'][$j++]['meta']['rating'];
  if($newest < $next){
      $successflag = 0;
      break;   
  }
}

if($successflag == 0){
  echo "<div class='failure'>FAILURE</div>, Page 1 did not sort correctly.<br>";
} else{
  echo "<div class='success'>SUCCESS</div>, Page 1 sorted correctly.<br>";
}

echo "<br>";

//Verifies that the Activity sorting is working correctly
echo "<h2>Verifies that Activity sorting is working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "activity";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

for($j=0; $j<$limits; $j++){
  $newest = $getExperiments_response['data'][$j]['session_count'];
  $next =  $getExperiments_response['data'][$j++]['session_count'];
  if($newest < $next){
      $successflag = 0;
      break;   
  }
}

if($successflag == 0){
  echo "<div class='failure'>FAILURE</div>, Page 1 did not sort correctly.<br>";
} else{
  echo "<div class='success'>SUCCESS</div>, Page 1 sorted correctly.<br>";
}

echo "<br>";

//Verifies that the Popularity sorting is working correctly
echo "<h2>Verifies that Popularity sorting is working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "popularity";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

for($j=1; $j<count($getExperiments_response['data']); $j++){
  $newest = $getExperiments_response['data'][$j-1]['contrib_count'];
  $next =  $getExperiments_response['data'][$j]['contrib_count'];
  if($newest < $next){
      $successflag = 0;
      break;   
  }
}

if($successflag == 0){
  echo "<div class='failure'>FAILURE</div>, Page 1 did not sort correctly.<br>";
} else{
  echo "<div class='success'>SUCCESS</div>, Page 1 sorted correctly.<br>";
}

echo "<br>";


//Verifies that the Recent sorting is working correctly
echo "<h2>Verifies that Recent sorting is working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "recent";
$action = "browse";

$getExperiments_response = getExperimentsTest($page, $limits, $query, $sort, $action);

for($j=1; $j<count($getExperiments_response['data']); $j++){

  $date1 = date($getExperiments_response['data'][$j-1]['meta']['timecreated']);
  $date2 =  date($getExperiments_response['data'][$j]['meta']['timecreated']);
  if($date2 > $date1){
      $successflag = 0;
      break;   
  }
}

if($successflag == 0){
  echo "<div class='failure'>FAILURE</div>, Page 1 did not sort correctly.<br>";
} else{
  echo "<div class='success'>SUCCESS</div>, Page 1 sorted correctly.<br>";
}

echo "<br>";

//--------------------------------------------------------------------------------------------------------------------

//Get People Test
echo "<h1>Get People Test</h1>";

//Verifies that the first page has different experiments to the ones on the following page
echo "<h2>Verifies that pages are working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "default";
$action = "browse";

$getPeople_response = getPeopleTest($page, $limits, $query, $sort, $action);
$result1= Array();

$i=0;

foreach($getPeople_response['data'] as $tmp){
  $result1[$i] = $tmp['user_id']; 
  $i++;
}

$page = 2;
$getPeople_response = getPeopleTest($page, $limits, $query, $sort, $action);

foreach($getPeople_response['data'] as $tmp){
  if(in_array($tmp['user_id'],$result1 )) {
      $successflag = 0;
  }else{
      $successflag = 1;
      break;
  }
}

  if($successflag == 0){
      echo "<div class='failure'>FAILURE</div>, Page 1 and 2 are not different.<br>";
  } elseif($successflag == 1){
      echo "<div class='success'>SUCCESS</div>, Page 1 and 2 are different.<br>";
  }

echo "<br>";
//Verifies that the number of people on a page does not exceed its limit
echo "<h2>Verifies that limits are working....</h2>";

$page = 1;
$limits = 10;
$query = "";
$sort = "default";
$action = "browse";

$getPeople_response = getPeopleTest($page, $limits, $query, $sort, $action);

  if(count($getPeople_response['data']) > $limits){
      echo "<div class='failure'>FAILURE</div>, Page 1 does not satisfy the limit requirement.<br>";
  } else{
      echo "<div class='success'>SUCCESS</div>, Page 1 satisfies the limit requirement.<br>";
  }

$page = 2;
$limits = 10;
$query = "";
$sort = "default";
$action = "browse";

$getPeople_response = getPeopleTest($page, $limits, $query, $sort, $action);





  if(count($getPeople_response['data']) > $limits){
      echo "<div class='failure'>FAILURE</div>, Page 2 does not satisfy the limit requirement.<br>";
  } else{
      echo "<div class='success'>SUCCESS</div>, Page 2 satisfies the limit requirement.<br>";
  }

echo "<br>";

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

// echo "More tests to come!"; What does this mean?
echo "<hr>";
?>