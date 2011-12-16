<?php 

require_once 'includes/config.php' ;

if (isset($_POST['submit'])) {

  //This is for all the money. We are submitting the data. Tell the user the outcome
  $smarty->assign('submission', 'true');

  //Grab the posted data
  $school         = $_REQUEST['school']; 
  $testType       = $_REQUEST['testType'];
  $team           = $_REQUEST['team']; 
  $sessionLoc     = $_REQUEST['sessionLoc'];
  $pH             = $_REQUEST['pH']; 
  $temp           = $_REQUEST['temp']; 
  $disox          = $_REQUEST['disox']; 
  $vernierClarity = $_REQUEST['vernierClarity']; 
  $secchiClarity  = $_REQUEST['secchiClarity']; 
  $airTemp        = $_REQUEST['airTemp']; 
  $copper         = $_REQUEST['copper'];
  $phosphorus     = $_REQUEST['phosphorus'];

  //Eid is hardcoded. If it changes, modify it here.
  $eid = 350;

  //Assign sessionLoc a value relative to the pre-existing session selected.    
  switch ($sessionLoc) {
    case "upriver":
      $sid = 2906;
      $lat = 42.6379998;
      $long = -71.3560938;
      break;
    case "downriver":
      $sid = 2891;
      $lat = 42.639705;
      $long = -71.354086;
      break;
    case "dock":
      $sid = 2905;
      $lat = 42.640084;
      $long = -71.352403;
      break;
    case "canals":
      $sid = 2904;
      $lat = 42.641031;
      $long = -71.328886;
      break;
  }

  $data = array(array($_SERVER['REQUEST_TIME'],$school . " " .  $team, $testType, $lat, $long, 
                      $temp, $pH, $vernierClarity, $secchiClarity, $disox, $copper, $phosphorus, $airTemp));
      
  putData($eid, $sid, $data);
  echo "<html><head><script type=\"text/javascript\">window.location=\"../vis.php?sessions=" . $sid . "\"</script></head></html>";

} else {

$smarty->assign('title', 'The Science of Rivers Rapid Entry Form');
$smarty->assign('content', $smarty->fetch('tsor.tpl'));
$smarty->display('skeleton.tpl');
}
?>


