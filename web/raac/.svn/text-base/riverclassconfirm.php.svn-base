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
      $eid = 200;
    ?>
    <h1 style="text-align:center;"> Is this correct? </h1>
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
    <span class="formLabel">secchiClarity(meter):</span>
      <span class="formData"> <?php print $secchiClarity; ?> </span><br/>
    <span class="formLabel">Air Temperature(Celsius):</span>
      <span class="formData"> <?php print $airTemp; ?> </span><br/>
    <span class="formLabel">Copper(ppm):</span>
      <span class="formData"> <?php print $copper; ?> </span><br/>
    <span class="formLabel">Phosphorus(ppm):</span>
      <span class="formData"> <?php print $phosphorus; ?> </span><br/>

    <?php 
      print  "<form method='post' action='riverclasssubmit.php'>
                <input type='hidden' name='school' value='$school' >
                <input type='hidden' name='testType' value='$testType'>
                <input type='hidden' name='team' value='$team'>
                <input type='hidden' name='sessionLoc' value='$sessionLoc'>
                <input type='hidden' name='pH' value='$pH'>
                <input type='hidden' name='temp' value='$temp'>
                <input type='hidden' name='disox' value='$disox'>
                <input type='hidden' name='vernierClarity' value='$vernierClarity'>
                <input type='hidden' name='secchiClarity' value='$secchiClarity'>
                <input type='hidden' name='airTemp' value='$airTemp'>
                <input type='hidden' name='copper' value='$copper'>
                <input type='hidden' name='phosphorus' value='$phosphorus'>
                <input type='submit' class='submitButton' name='submit' value='This is correct!'/>
              </form>";
    ?>
  </body>
</html>
