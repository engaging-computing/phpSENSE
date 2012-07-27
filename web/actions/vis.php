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

require_once '../includes/config.php';
error_reporting(E_ALL);
//echo "Hi?";

$errors = array();
$result = -1;
if(isset($_GET['action'])) {
    
    switch($_GET['action']) {
        case "save":
            if(isUser()){
                $eid = -1;
                if(isset($_GET['eid'])) { $eid = (int) safeString($_GET['eid']); }
                if($eid == -1) { array_push($errors, 'You did not set the experiment id!'); }
                
                $name = "";
                if(isset($_GET['name'])) { $name = safeString($_GET['name']); }
                if($name == -1) { array_push($errors, 'You did not set the name!'); }
                
                $desc = "";
                if(isset($_GET['desc'])) { $desc = safeString($_GET['desc']); }
                if($desc == -1) { array_push($errors, 'You did not set the description!'); }
                
                $url_params = "";
                if(isset($_GET['url_params'])) { $url_params = safeString($_GET['url_params']); }
                if($url_params == "") { array_push($errors, 'You did not provide arguments for your visualization.'); }
                
                $sessions = "";
                if(isset($_GET['sessions'])) { $sessions = safeString($_GET['sessions']); }
                if($sessions == "") { array_push($erros, 'You did not provide any sessions'); }
                $sessions = split(",", $sessions);
                
                $uid = -1;
                if($session->userid > 0) { $uid = (int) $session->userid; }
                if($uid == -1) {  array_push($errors, 'You are not logged in!'); }
                
                // Check to see if experiment is an activity
                $is_activity = (isActivity($eid) ? 1 : 0);
                
                if(count($errors) == 0) {
                    $result = createNewVis($uid, $eid, $name, $desc, $sessions, $url_params, $is_activity);
                }	
            }		
            break;
    }
    
    if(count($errors) > 0) {
        foreach($errors as $e) {
            echo  $e . '<br/>';
        }
    }
    else {
        echo $result;
    }
}

?>
