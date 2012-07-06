<html>
<head>
<title>iSenseDev Automated Testing</title>
<link rel="stylesheet" type="text/css" href="apitest.css" />
</head>
<body>

<?php

//To do:
// getPeople
// getSessions (deal with limits)
// getExperiments (make sure you deal with limits)
// putSessionData/updateSessionData
// uploadImageToExperiment
// uploadImageToSession


//DONE!!!
// ***apitest-login.php***
// login

// ***apitest-get_general_info.php***
// getExperiments
// getPeople
// getVisualizations
// getSessions
// getDataSince

//DONE!!!
// ***apitest-get_experiment_info.php***
// getExperimentFields
// getExperimentVisualizations
// getExperimentTags
// getExperimentVideos
// getExperimentImages

//DONE!!!
// ***apitest-get_user_info.php***
// getExperimentByUser
// getVisByUser
// getImagesByUser
// getVideosByUser
// getUserProfile
// getSessionsByUser

// ***apitest-session.php***
// createSession
// uploadImageToSession
// putSessionData

// ***apitest-experiment.php***
// uploadImageToExperiment

// Note to Jim: Need to fix mysql error - user -1 has images, otherwise Get Images By User Test fails

require_once('../../includes/config.php');

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

echo "<div class=\"testheading\">Starting login test....<br></div>";

require_once('apitest-login.php');

echo "<div class=\"testheading\">Starting Experiment Test...<br></div>";

require_once('apitest-experiment.php');

echo "<div class=\"testheading\">Starting Session Test...<br></div>";

require_once('apitest-session.php');

echo "<div class=\"testheading\">Starting Get General Info Test...<br></div>";

require_once('apitest-get_general_info.php');

echo "<div class=\"testheading\">Starting Get User Info Test...<br></div>";

require_once('apitest-get_user_info.php');

echo "<div class=\"testheading\">Starting Get Experiment Info Test...<br></div>";

require_once('apitest-get_experiment_info.php');

?>
</body>
</html>