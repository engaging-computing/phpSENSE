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

define('IDENTIFY', 		    1);
define('CORRECT_FIELDS', 	2);
define('CORRECT_TIME',		3);
define('STORE',			    4);

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

if( $type == 'manual' && $state == CORRECT_TIME )
    $state = 4;

if(isset($_REQUEST['state'])) {
	$state = (int) safeString($_REQUEST['state']);
}
else {
	$state = IDENTIFY;
}

if(isset($_POST['timefix'])) {
	$timefix = $_POST['timefix'];
}

if(isset($_POST['columnfix'])) {
	$columnfix = $_POST['columnfix'];
}

if(isset($_REQUEST['id'])) {

	$eid = ((isset($_POST['id'])) ? $_POST['id'] : $_GET['id']);
	if(($meta = getExperiment($eid))) {
		$ownerid 	= $meta['owner_id'];
		$title 		= ucwords($meta['name']) . " - Add New Session";
		$fields 	= getFields($eid);
	}
	
	//$fields = array_slice($fields, 1);
	
	$smarty->assign('meta', 		$meta);
	$smarty->assign('title', 		$title);
	$smarty->assign('fields', 		$fields);
	$smarty->assign('field_count', 	count($fields));
    $smarty->assign('e_proc', $meta['description']);
    
    if(!isNameRequired($eid)){ 
	    $req_name = 0;
	} else { 
	    $req_name = 1; 
	}
	
	if(!isLocationRequired($eid)) {
	    $req_loc = 0;
	} else {
	    $req_loc = 1;
	}
	
	if(!isProcedureRequired($eid)) {
	    $req_procedure = 0;
	} else {
	    $req_procedure = 1;
	}
}
else {
	array_push($errors, 'Could not find your experiment.');
}



// Check to see if we have started the form process yet
if(isset($_POST['session_create']) && count($errors) == 0) {
	
	$type = isset($_POST['session_type']) ? safeString($_POST['session_type']) : "file";
	$smarty->assign('session_type', $type);
	
	// Check to ensure we get all the experiment meta
	$post_data = array(	'session_name' => "", 
				'session_description' => "", 
				'session_street' => "", 
				'session_citystate' => "", 
				'session_type' => "");

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
		
		if(!$req_name) { $post_data['session_name'] = getSessionPrefix($eid) . getSessionOffset($eid); }
		if(!$req_procedure) { $post_data['session_description'] = ''; }
		if(!$req_loc) { 
		    $post_data['session_citystate'] = getExperimentLocation($eid);
	        $post_data['session_street'] = ''; 
	    }

	}
	
	if($type == "file") {
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

		$file = file($filename);

		if($state == IDENTIFY) {

			//$needs_column_fix = false; - Doesn't look like this is used anywhere
			//$needs_time_fix = false; - This also doesn't look like its used anywhere
			$is_verneir = false;

			$mapping = array();
			$mismatch_count = 0;

			$debug_data = "";
			$file_data = array();
			$header = array();

			if(count($errors) == 0) {
				// Need to do attempt to match up fields
				foreach($file as $f) {
					if(strpos($f, "#") !== false) {
						$debug_data .= $f . " ";
					}
					else {
						array_push($file_data, explode(",", $f));
					}
				}
				
				// Grab the header
				$header = $file_data[0];
				$first_header = $header[0];
				$first_data = $file_data[1];
				
				// if($debug) {
                //    echo "Check first header " . substr($first_header, 0, 1) . "<br/>";
                // }

				// Check to see if it is a verneir file
				// if(substr($first_header, 0, 1) == "\"") {
				//    if($debug) echo "Is Verneir is True<br/>";
				//	$is_verneir = True;
				//	$timefix = 1;
				// }
				
				// Clean header
				for($i = 0; $i < count($header); $i++) {
					$header[$i] = str_replace("\"", "", $header[$i]);
				}
				
				// Do some nonsense to see if a pinpoint has failed
				for($i = 0; $i < count($header); $i++) {
					$h = $header[$i];
					
					// Find the time header
					if(strcasecmp($h, "time") == 0 && $fields[0]['type_id'] == TIME_TYPE_ID) {

						// Check to see if the first element is 0
						if($first_data[$i] == 0 && !is_nan($first_data[$i])) {
							$timefix = 1;
							break;
						}
					}
				}

				if(false) {
					if($is_verneir) { echo "This is a verneir file"; } else { echo "This not a verneir file"; }
					echo "<br/>";

					if($timefix == 1) { echo "This file needs a time fix"; } else { echo "This is not a time fix"; }
					echo "<br/>";
				}
				
				// Init mapping array
				foreach($fields as $f) {
					$mapping[] = -1;
				}

				for($i = 0; $i < count($mapping); $i++) {

					$field_name = strtolower($fields[$i]['field_name']);

					for($j = 0; $j < count($header); $j++) {
						$h = trim(strtolower(str_replace("\"", "", $header[$j])));
						if($mapping[$i] == -1 && strcmp($h, $field_name) == 0) {
							$mapping[$i] = $j;
						}
					}
				}

				// Lets count how many items we didn't match
				for($i = 0; $i < count($mapping); $i++) { 
					if($mapping[$i] == -1) { 
						$mismatch_count++; 
					} 
				}
				
				if($debug) {
					for($i = 0; $i < count($mapping); $i++) {
						echo "{$fields[$i]['field_name']} matches {$header[$mapping[$i]]}<br/>";
					}
					
					echo "Mismatch Count: " . $mismatch_count . "<br/>";
				}

				if($mismatch_count != 0) {
					$state = CORRECT_FIELDS;

					/* Populate list of fields that are unmatched */
					$unmatched_fields = array();
					for($i = 0; $i < count($mapping); $i++) {
						if($mapping[$i] == -1) { array_push($unmatched_fields, array($i, $fields[$i]['field_name'])); }
					}

					/* Populate list of headers that are unmatched */
					$unmatched_header = array();
					for($i = 0; $i < count($header); $i++) {
						if(!in_array($i, $mapping)) {
							array_push($unmatched_header, array($i, $header[$i]));
						}
					}

					$smarty->assign('unmatched_fields', $unmatched_fields);
					$smarty->assign('unmatched_header', $unmatched_header);
				}
				else {
					$new_data = array();

					// Fix the header
					for($i = 0; $i < count($mapping); $i++) {
						$new_data[0][] = trim(strtolower(str_replace("\"", "", $header[$mapping[$i]])), "\r\n");
					}

					// Fix the data payload
					for($j = 1; $j < count($file_data); $j++) {
						for($i = 0; $i < count($mapping); $i++) {
							$new_data[$j][] = trim($file_data[$j][$mapping[$i]], "\r\n");
						}
					}

					// Write the new data to a file
					$filename = tempnam('/tmp', 'FOO');
					$fp = fopen($filename, 'w+');

					if($debug) echo "Temp File Name: {$filename}<br/>"; 

					for($j = 0; $j < count($new_data); $j++) {
						$line = $new_data[$j];
						$output = "";

					    for($i = 0; $i < count($line); $i++) {
							$output = $output . $line[$i] . ",";
						}

						$output = substr($output, 0, (strlen($output)-1));
						$output = $output . "\r\n";

						fwrite($fp, $output);
					}

					fclose($fp);
					
					if($is_verneir == true || $timefix == 1) {
						$state = CORRECT_TIME;
					}
					else {
						$state = STORE;
					}
				}
			}
		}
		// Does the field align
		else if($state == CORRECT_FIELDS) {

			$unmatched_count = -1;
			$mapping = array();

			$file_data = array();
			$new_data = array();
			$header = array();

			if(isset($_POST['unmatched_field_count'])) { $unmatched_count = (int) safeString($_POST['unmatched_field_count']); }
			if($unmatched_count == -1) { array_push($errors, 'There was an issue regarding your additional field input.'); }

			// Need to do attempt to match up fields
			foreach($file as $f) {
				if(strpos($f, "#") === false) {
					array_push($file_data, explode(",", $f));
				}
			}

			$header = $file_data[0];
			foreach($fields as $f) {
				$mapping[] = -1;
			}

			for($i = 0; $i < count($mapping); $i++) {

				$field_name = strtolower($fields[$i]['field_name']);

				for($j = 0; $j < count($header); $j++) {
					$h = trim(strtolower(str_replace("\"", "", $header[$j])));

					if($mapping[$i] == -1 && strcmp($h, $field_name) == 0) {
						$mapping[$i] = $j;
					}
				}
			}

			for($i = 0; $i < $unmatched_count; $i++) {
				$f = $_POST['field_'.$i];
				$h = $_POST['header_'.$i];
				$mapping[$f] = $h;
			}

			// This looks familiar doesn't it?
			for($i = 0; $i < count($mapping); $i++) {

				$field_name = strtolower($fields[$i]['field_name']);

				for($j = 0; $j < count($header); $j++) {
					$h = trim(strtolower(str_replace("\"", "", $header[$j])));

					if($mapping[$i] == -1 && strcmp($h, $field_name) == 0) {
						$mapping[$i] = $j;
					}
				}
			}

			$mismatch_count = 0;
			for($i = 0; $i < count($mapping); $i++) { 
				if($mapping[$i] == -1) { 
					$mismatch_count++; 
				} 
			}

			if($mismatch_count == 0) {
				// Fix the header
				for($i = 0; $i < count($mapping); $i++) {
					//BIG CHANGE RIGHT HERE
					$new_data[0][] = trim(strtolower(str_replace("\"", "",$fields[$i]['field_name'])), "\r\n");
				}

				// Fix the data payload
				for($j = 1; $j < count($file_data); $j++) {
					for($i = 0; $i < count($mapping); $i++) {
						$new_data[$j][] = trim($file_data[$j][$mapping[$i]], "\r\n");
					}
				}

				// Write the new data to a file
				$filename = tempnam('/tmp', 'FOO');
				$fp = fopen($filename, 'w+');

				for($j = 0; $j < count($new_data); $j++) {
					$line = $new_data[$j];
					$output = "";

				    for($i = 0; $i < count($line); $i++) {
						$output = $output . $line[$i] . ",";
					}

					$output = substr($output, 0, (strlen($output)-1));
					$output = $output . "\r\n";

					fwrite($fp, $output);
				}

				if($timefix == 1) {
					$state = CORRECT_TIME;
				}
				else {
					$state = STORE;
				}
			}
		}
		// Does the time fix
		else if($state == CORRECT_TIME) {

            $new_data = array();
			$first_data = array();
			$file_data = array();
			$header = array();
			$time_index = -1;

			// Check to ensure we get all the experiment meta
			$date = array(			'date' => '01/01/1970',
									'hour' => 0, 
									'minute' => 0,
									'part' => 'AM');


			foreach($date as $k => $v) {
				if(isset($_POST[$k])) {
					if($_POST[$k] != "") {
						$smarty->assign($k, safeString($_POST[$k]));
						$date[$k] = safeString($_POST[$k]);
					}
					else {
						$split = explode("_", $k);
						array_push($errors, ucwords($split[0]) . ' '. $split[1] . ' can not be blank.');
					}
				}
			}

			if(count($errors) == 0) {

				$split = explode("/", $date['date']);
				
				$date['day'] 	= $split[1];
				$date['month'] 	= $split[0];
				$date['year'] 	= $split[2];

				if($date['part'] == "PM") {
					if($date['hour'] == 12) {
						$date['hour'] = 0;
					}
					else {
						$date['hour'] = $date['hour'] + 12;
					}
				}

				$time = $date['year'] . "-" . $date['month'] . "-" . $date['day'] . " " . $date['hour'] . ":" . $date['minute'] . ":00";
				$time = strtotime($time) * 1000; // Multiply by 1000 to convert to Milliseconds

				// Load the file data into an array
				foreach($file as $f) {
					if(strpos($f, "#") === false) {
						array_push($file_data, explode(",", $f));
					}
				}

				$index = -1;
				$header = $file_data[0];
				$first_data = $file_data[1];
				
				// Find the index of the time column
				for($i = 0; $i < count($header); $i++) {

					$h = trim(strtolower(str_replace("\"", "", $header[$i])));
					if(strcasecmp($h, "time") == 0) {
						$index = $i;
					}
				}
				
				// This looks fimilar doesn't it?
				$mapping = array();
				
				// Init mapping array
				foreach($fields as $f) {
					$mapping[] = -1;
				}
				
				if($index != -1) {

                    $new_data = array();
                    
                    $first_time = $file_data[1][$index];
                    $second_time = $file_data[2][$index];
                    $interval = ($second_time - $first_time);

					for($i = 1; $i < count($file_data); $i++) {
						$file_data[$i][$index] = $time + ((($i-1) * $interval) * 1000); // Multiply by 1000 to make it miliseconds...
					}

                    if($mismatch_count > 0) {
						// Fix the header
	    				for($i = 0; $i < count($mapping); $i++) {
	    					$new_data[0][] = trim(strtolower(str_replace("\"", "",$header[$mapping[$i]])), "\r\n");
	    				}
	
	    				// Fix the data payload
	    				for($j = 1; $j < count($file_data); $j++) {
	    					for($i = 0; $i < count($mapping); $i++) {
	    						$new_data[$j][] = trim($file_data[$j][$mapping[$i]], "\r\n");
	    					}
	    				}
					}
					else {
						$new_data = $file_data;
					}
					
					// Write the new data to a file
					$filename = tempnam('/tmp', 'FOO');
					$fp = fopen($filename, 'w+');
					
					for($j = 0; $j < count($new_data); $j++) {
						$line = $new_data[$j];
						$output = "";

					    for($i = 0; $i < count($line); $i++) {
							$output = $output . trim(str_replace("\"", "", $line[$i]), "\r\n") . ",";
						}

						$output = substr($output, 0, (strlen($output)-1));
						$output = $output . "\r\n";

						fwrite($fp, $output);
					}

    				$state = STORE;
				}
			}
		}

		// Special state to skip to store
		if($state == STORE) {
			
			$debug_data = str_replace("\n", "", $debug_data);
			$debug_data = str_replace("\r", "", $debug_data);
			$debug_data = str_replace("#", "", $debug_data);

			$sid = createSession(	$session->generateSessionToken(), 
									$eid, 
									safeString(stripslashes($post_data['session_name'])), 
									safeString(stripslashes($post_data['session_description'])), 
									safeString(stripslashes($post_data['session_street'])), 
									safeString(stripslashes($post_data['session_citystate'])), 
									"", // Default country
									1, // Default permissions bit
									1, // Default permissions bit
									1, // Default permissions bit
									$debug_data);
            
            // Open the file
            $file = fopen($filename, "r");
            
            // Setup Data Set
            $data_set = array();
			$first = TRUE;
            
            // Setup the variables we need to find the time field
            $now = time();
            $time_index = -1;
            $time_fail = false;
            $f_count = count($fields);
            
            // Find the time index
            for($i = 0; $i <  $f_count && $time_index == -1; $i++) {
                if($fields[$i]['type_id'] == TIME_TYPE_ID) {
                    $time_index = $i;
                }
            }

            // Read the csv from the file
            while(($data = fgetcsv($file)) !== FALSE) {
                
                // This is used to skip the first line
				if($first === FALSE) {
				    
				    // Create a new line for data
					$line = array();
					$dcount = count($data);
					
	                // Seems like it should work...
	                for($i = 0; $i < $dcount; $i++) {
	                    
	                    
	                    if($time_index == $i) {
	                        if((preg_match('/[a-z]+/i', $data[$i]) != 0) || strpos($data[$i], "/") !== FALSE) {
	                            $line[] = strtotime($data[$i]) * 1000;
	                        } else {
	                            if( $data[$i] > 2000000000)
        			                $line[] = intval($data[$i]);
        			            else
        			                $line[] = intval($data[$i]) * 1000;
	                            //$line[] = ($data[$i] > 0) ? intval($data[$i]) : -1 * intval($data[$i]);
                            }
	                    }
	                    else {
	                        $line[] = $data[$i];
	                    }
	                    
	                }
                    
                    // Add the line to the data set
	                $data_set[] = $line;
				}
				
				$first = FALSE;
            }
                                    
            putData($eid, $sid, $data_set);
            
			/* Check for errors, set done flag */
			if(count($errors) == 0) {
				$smarty->assign('session', $sid);
				$done = true;
			}
		}

		$smarty->assign('debug_data', $debug_data);
		$smarty->assign('target_path', $filename);
	}
	else if($type == "manual") {	
	    

	    // Set the debug data to an empty string, as there is none	
		$debug_data = "";
		
		// Create the session record
        $sid = createSession(	$session->generateSessionToken(), 
        						$eid, 
        						safeString($post_data['session_name']), 
        						safeString($post_data['session_description']), 
        						safeString($post_data['session_street']), 
        						safeString($post_data['session_citystate']), 
        						"", // Default country
								1, // Default permission bits
								1, // Default permission bits
								1, // Default permission bits
								$debug_data);
		
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
		    
		    // Iterate through each feild, or each column in the row of data
		    foreach($fields as $key => $field) {
          	        
 		      // Get the name of the feild
          $name = str_replace(" ", "_", $field['field_name']) . "_" . $i;
			    $val = safeString($_POST[$name]);
			    

          // Check to see if this is a time value
			    if($field['type_id'] == TIME_TYPE_ID) {
			            
			        // Check to see if there are words in the val
			        if((preg_match('/[a-z]+/i', $val) != 0) || strpos($val, "/") !== FALSE) {
			            
			            // Try and parse the ridiculous date format, and hope to god it works
			            if(($new_time = strtotime($val)) !== FALSE && $time_fail == FALSE) {
			                $x[] = intval($new_time * 1000);
			            }
			        }
            //If first data point is one of these values, assume incremental time.
              else if( $man_off || ($val == "0" || $val == "0.0" || $val == "1.0" || $val == "1" ) ) {
		                // If so assume incremental seconds from upload
		                $x[] = $now + ($val * 1000) . "";
				$man_off = 1;
		                // Don't trust the time format from now on
		                // force the seconds from upload
		                $time_fail = true;
		            }
			        else if(strpos($val, ".") !== FALSE) {
			            echo "Got to decimal case!<br/>";
			            $x[] = (string) ( ( (double) $val ) * 1000 );
              }

              // Assume anything greater than 2 billion is actually already in miliseconds
              // Bug will be here in 2033. You found me if you are looking.
			        else {
			            if( $val > 2000000000)
			                $x[] = intval($val);
			            else
			                $x[] = intval($val) * 1000 ;
			        } 
           } 
                
           else {
             // This value is not a time type, so we directly insert into the row
			       $x[] = $val;
			     }
		    }
		    
		    // Append this row to the greater data set
		    $data_set[] = $x;
		}

		//print_r($data_set);
		
		// Put this dataset into the db
		putData($eid, $sid, $data_set);
		
		// Check for errors, set done flag
		if(count($errors) == 0 && $state != CORRECT_TIME) {
			$smarty->assign('session', $sid);
			$done = true;
			$state = 4;
		}
	}
}


// Assign values specific for this view
$smarty->assign('state', 		$state);
$smarty->assign('errors', 		$errors);
$smarty->assign('time_fix',		$timefix);
$smarty->assign('column_fix',	$columnfix);

// Hide Name, Procedure and Location
$smarty->assign('hideName', $req_name);
$smarty->assign('hideProcedure', $req_procedure);
$smarty->assign('hideLocation', $req_loc);

$smarty->assign('head', '<script src="/html/js/lib/jquery.validate.js"></script>' . 
						'<script src="/html/js/lib/validate.js"></script>'.
						'<script src="/html/js/lib/MillisecondClock.js"></script>');

// Assign values required for all views
$smarty->assign('user', 	    $session->getUser());
$smarty->assign('content', 	    $smarty->fetch('upload.tpl'));

// Send this Bad Larry to standard out
$smarty->display('skeleton.tpl');

?>
