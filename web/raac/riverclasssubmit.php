<html>
  <head>
    <title> River as a Classroom Easy Submission Review </title>
    <link rel="stylesheet" type="text/css" href="main.css"/>
  </head>
  <body>
  <?php require_once '../includes/config.php' ?>
  <div id="wrapper">
    <div>
      <img src="../html/img/logo.png"/>
    </div>
    <?php 

      // Map variables to posted data
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

      // eid is hardcoded. If this changes, modify it here.
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
    ?>
      <span class="formLabel">School Name:</span>
        <span class="formData"> <?php print $school; ?> </span><br/>
      <span class="formLabel">Team:</span>
        <span class="formData"> <?php print $team; ?> </span><br/>
      <span class="formLabel">Test Type:</span>
        <span class="formData"> <?php print $testType; ?> </span><br/>
      <span class="formLabel">Location:</span>
        <span class="formData"> <?php print $sessionLoc; ?> </span><br/>
      <span class="formLabel">pH:</span>
        <span class="formData"> <?php print $pH; ?> </span><br/>
      <span class="formLabel">Water Temperature(Celsius):</span>
        <span class="formData"> <?php print $temp; ?> </span><br/>
      <span class="formLabel">Dissolved Oxygen(ppm):</span>
        <span class="formData"> <?php print $disox; ?> </span><br/>
      <span class="formLabel">Vernier Clarity(ntu):</span>
        <span class="formData"> <?php print $vernierClarity; ?> </span><br/>
      <span class="formLabel">Secchi Clarity(meter):</span>
        <span class="formData"> <?php print $secchiClarity; ?> </span><br/>
      <span class="formLabel">Air Temperature(Celsius):</span>
        <span class="formData"> <?php print $airTemp; ?> </span><br/>
      <span class="formLabel">Copper(ppm):</span>
        <span class="formData"> <?php print $copper; ?> </span><br/>
      <span class="formLabel">Phosphorus(ppm):</span>
        <span class="formData"> <?php print $phosphorus; ?> </span><br/>

      <?php 
        $data = array(array($_SERVER['REQUEST_TIME'],$school . " " .  $team, $testType, $lat, $long, $temp, $pH, $vernierClarity, $secchiClarity, $disox, $copper, $phosphorus, $airTemp));
      ?>
      <?php putData($eid, $sid, $data); ?>
      <a href="../vis.php?sessions=<?php print $sid; ?>"?> View this submission </a>
    </div>
  </body>
</html>
