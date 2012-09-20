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

require_once 'includes/config.php';
ini_set('upload_tmp_dir', '/tmp');

define('IDENTIFY',                  1);
define('CORRECT_FIELDS',        2);
define('CORRECT_TIME',          3);
define('STORE',                     4);

define('TIME_TYPE_ID',      7);


//Notes: Smarty isn't writing the timefix and columnfix values out like it should
$state = -1;
$timefix = 0;
$columnfix = 0;
$errors = array();

$debug = false;

$eid = -1;
$ownerid = -1;
$title = "";

$fields = array();
$field_count = -1;

if(isset($_REQUEST['id'])) {
    
    $eid = ((isset($_POST['id'])) ? $_POST['id'] : $_GET['id']);
    if(($meta = getExperiment($eid))) {
        $ownerid        = $meta['owner_id'];
        $title          = ucwords($meta['name']) . " - Add New Session";
        $fields         = getFields($eid);
        $smarty->assign('eid',             $eid);
        $smarty->assign('meta',             $meta);
        $smarty->assign('title',            $title);
        $smarty->assign('fields',           $fields);
        $smarty->assign('field_count',      count($fields));
        $smarty->assign('e_proc', $meta['description']);
        
        //Determine what meta data is required for this experiment.
        $req_name = isNameRequired($eid);
        $req_loc = isLocationRequired($eid);
        $req_procedure = isProcedureRequired($eid);
        
    } else {
        array_push($errors, 'Could not find your experiment.');
    }
} else {
    array_push($errors, 'Could not find your experiment.');
}

// Check to see if we have started the form process yet
if(isset($_POST['session_create']) && count($errors) == 0) {
    
    $type = isset($_POST['session_type']) ? safeString($_POST['session_type']) : "file";
    $smarty->assign('session_type', $type);
    $type = $_POST['session_type'];
    // Check to ensure we get all the experiment meta
    $post_data = array( 'session_name' => "", 
    'session_description' => "", 
    'session_citystate' => "", 
    'session_type' => "");
    
    if(!$req_name) { 
        $_POST['session_name'] = getSessionPrefix($eid) . getSessionOffset($eid); 
    }
    if(!$req_procedure) { 
        $_POST['session_description'] = ' '; 
    }
    if(!$req_loc) { 
        $_POST['session_citystate'] = getExperimentLocation($eid);
        $_POST['session_street'] = ' '; 
    }
    
    foreach($post_data as $k => $v) {
        if(isset($_POST[$k])) {
            if($_POST[$k] != "") {
                $post_data[$k] = stripslashes($_POST[$k]);
                $smarty->assign($k, stripslashes($_POST[$k]));
            }
            else {
                $split = explode("_", $k);
                array_push($errors, ucwords($split[0]) . ' '. $split[1] . ' can not be blank.');
            }
        }
    }
    
    if($type == "manual") {      
        // Set the debug data to an empty string, as there is none      
        $debug_data = "";

        // Setup the array to store our cleaned data
        $data_set = array();
        
        // Get the number of rows we'll need to interate through
        $row_count = isset($_POST['row_count']) ? ((int) safeString($_POST['row_count']) + 1) : 1;
        
        $now = time() * 1000;   // Used to increment time from upload
        $time_fail = false;     // Used to track wether of not we can parse the format
        
        $tmt = 0;
        
        $man_off = 0;

        // Iterate through each row of data
        for($i = 1; $i < $row_count; $i++) {
            $x = array();
            
            foreach($fields as $key => $field) {
                $name = str_replace(" ", "_", $field['field_name']) . "_" . $i;
                $val = safeString($_POST[$name]); 
                $x[] = $val;
            }
            $data_set[] = $x; 
        }
        
        $data = $data_set;
        
        $data = fixTime($data,$eid);
        
        // Create the session record
        $sid = createSession(   $session->generateSessionToken(), 
                                $eid, 
                                safeString($post_data['session_name']), 
                                           safeString($post_data['session_description']), 
                                           safeString(""), 
                                safeString($post_data['session_citystate']), 
                                           "", // Default country
                                           1, // Default permission bits
                                           1, // Default permission bits
                                           1, // Default permission bits
                                           $debug_data);
                                putData($eid,$sid,$data);
        header("Location: highvis.php?sessions={$sid}");
        
    } else if ($type == "file"){
        if(!isset($_POST['filename'])){
            
            //VALIDATE THE FILE EXISTS AND IS A CSV
            $debug_data = (isset($_POST['debug_data'])) ? safeString($_POST['debug_data']) : "";
            $filename = (isset($_POST['target_path'])) ? safeString($_POST['target_path']) : NULL;
            
            if($filename == NULL) {
                $filename = '/tmp/' . basename($_FILES['session_file']['name']); 
                
                // Mime Type Check
                $mime = mime_content_type($_FILES['session_file']['tmp_name']);
                
                $accepted_mimes = array(
                    'text/comma-separated-values',
                    'text/plain',
                    'text/csv'
                    );
                    
                    if(!in_array($mime, $accepted_mimes)) {
                        array_push($errors, 'You attempted to upload a file that is not a CSV.');
                    }
                    
                    if(count($errors) == 0) {
                        if(!move_uploaded_file($_FILES['session_file']['tmp_name'], $filename)) {
                            array_push($errors, 'Error uploading file!');
                        }
                    }
                    else {
                        unlink($_FILES['session_file']['tmp_name']);
                    }
                    
            }
            
            if($filename == NULL) {
                array_push($errors, 'You did not specify an upload file.');
            }
            
            //Sanitize csv contents
            sanitizeFile($filename);
            
            //Turn the file into JSON 
            $data_pre_match = getJSONFromFile($filename);
            
            //Get column matches
            $matches = getColumnMatches($data_pre_match, $eid);

            //Check if all matched
            if($matches['mismatched_count'] > 0){
                
                /* Populate list of fields that are unmatched */
                $unmatched_fields = array();
                $tmp = $matches['matches'];
                for($i=0; $i<count($tmp); $i++){
                    if($tmp[$i] == -1){
                        array_push($unmatched_fields, array($i, $fields[$i]['field_name'])); 
                    }
                }
                
                /* Populate list of headers that are unmatched */
                $unmatched_header = array();
                for($i = 0; $i < count($data_pre_match['headers']); $i++) {
                    if(!in_array($i, $tmp)) {
                        array_push($unmatched_header, array($i, $data_pre_match['headers'][$i]));
                    }
                }
                
                $smarty->assign('unmatched_fields', $unmatched_fields);
                $smarty->assign('unmatched_headers', $unmatched_header);
                $smarty->assign('filename', $filename);
                $smarty->assign('flag', 1); 
                
                /*Right here this will jump into the ajax call from javascript in smarty
                To do field matching and time fixing.*/
                
            } else { 
                
                /*Fields do not need to be matched by hand, still need to shuffle and time fix*/
                $data = shuffleColumns($filename,$eid);
                $data = fixTime($data,$eid);
                
                // Create the session record
                $sid = createSession(   $session->generateSessionToken(), 
                                        $eid, 
                                        safeString($post_data['session_name']), 
                                        safeString($post_data['session_description']), 
                                        safeString(""), 
                                        safeString($post_data['session_citystate']), 
                                        "", // Default country
                                        1, // Default permission bits
                                        1, // Default permission bits
                                        1, // Default permission bits
                                        $debug_data);
                putData($eid,$sid,$data);
                  header("Location: highvis.php?sessions={$sid}");
            }
            
        }
    } 
}



// Assign values specific for this view
$smarty->assign('closed',       $meta['closed']);
$smarty->assign('state',                $state);
$smarty->assign('errors',               $errors);
$smarty->assign('time_fix',             $timefix);
$smarty->assign('column_fix',   $columnfix);

// Hide Name, Procedure and Location
$smarty->assign('hideName', $req_name);
$smarty->assign('hideProcedure', $req_procedure);
$smarty->assign('hideLocation', $req_loc);



// Assign values required for all views
$smarty->assign('user',             $session->getUser());
$smarty->assign('head', '<script src="/html/js/lib/jquery.validate.js"></script>' . 
                        '<script src="/html/js/lib/validate.js"></script>'.
                        '<link rel="stylesheet" type="text/css" href="/html/css/jquery-ui.css"></link>');

if(strpos($_SERVER['HTTP_USER_AGENT'],'Android')!= true){
    $smarty->assign('content',          $smarty->fetch('upload.tpl'));
    $smarty->display('skeleton.tpl');
} else {
    $smarty->display('mobile/contribute.tpl');
}
?>
