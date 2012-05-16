<?php
/* Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

require_once 'includes/config.php' ;

if (isset($_REQUEST['submit'])) {

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
      
  //print_r($data);

  putData($eid, $sid, $data);
  echo "<html><head><script type=\"text/javascript\">window.location=\"../vis.php?sessions=" . $sid . "\"</script></head></html>";

} else {
	
	$smarty->assign('head', '<script src="/html/js/lib/jquery.validate.js"></script>' . 
							'<script src="/tsor/validate.js"></script>');

$smarty->assign('title', 'The Science of Rivers Rapid Entry Form');
$smarty->assign('content', $smarty->fetch('tsor.tpl'));
$smarty->display('skeleton.tpl');
}
?>


