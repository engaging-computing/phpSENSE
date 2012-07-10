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

// Initalize authentication state to false, ie not logged in
$authenticated = false;

// Check for method parameter
$method = (isset($_REQUEST['method']) ? safeString($_REQUEST['method']) : null);

// Check for session key
$session_key = (isset($_REQUEST['session_key']) ? safeString($_REQUEST['session_key']) : null);

// Initalize Output Variables
$status = 600;
$data = null;

// Check to see if session key is null, if not then log in
if($session_key != null) {
	$session->start_rest_session($session_key);
}
else {
    
    // Check to see if the request is to login, if so login
    if(strcasecmp($method, "login") == 0) {
        
    }
    
}



if(isset($_REQUEST['method'])) {
	$method = safeString($_REQUEST['method']);
	
	switch($method) {
		
		case "login":
		    $username = safeString($_REQUEST['username']);
			$password = safeString($_REQUEST['password']);
			
			if($session->login($username, $password)) {
				$data = $session->generateSessionToken();
				$status = 200;
			}
			
			break;		
					
	    case "getExperiments":
			// Setup the default params
			$params = array(
							"page" => 1,
							"limit" => 10,
							"query" => "",
							"sort" => "default",
							"action" => "browse",
							"type" => "experiments",
						);

			// Check to see if values are set, overwrite defaults if set
			foreach($params as $k => $v) {
				if(isset($_REQUEST[$k])) {
					$params[$k] = strtolower(safeString($_REQUEST[$k]));
				}
			}

			$action = $params['action'];
			$type = $params['type'];
			$query = $params['query'];
			$page = $params['page'];
			$limit = $params['limit'];
			$sort = $params['sort'];

			$data = browseExperiments($page,$limit,0,"off","off",$query,$sort);
			
			if(count($data) > 0) {
			    
				for($i = 0; $i < count($data); $i++) {
				    $imgs = getImagesForExperiment($data[$i]['meta']['experiment_id']);
				    if(count($imgs) > 0) {
				        $data[$i]['meta']['provider_url'] = $imgs[0]['provider_url'];
				    }
				    else {
				        $data[$i]['meta']['provider_url'] = null;
				    }
				}
			}
          
			$status = 200;
			break;

		case "getPeople":
		    // Setup the default params
		    $params = array(
						    "page" => 1,
						    "limit" => 10,
						    "query" => "",
						    "sort" => "default",
						    "action" => "browse",
						    "type" => "people",
						);

		    // Check to see if values are set, overwrite defaults if set
		    foreach($params as $k => $v) {
			    if(isset($_REQUEST[$k])) {
				    $params[$k] = strtolower(safeString($_REQUEST[$k]));
			    }
		    }

		    $action = $params['action'];
		    $type = $params['type'];
		    $query = $params['query'];
		    $page = $params['page'];
		    $limit = $params['limit'];
		    $sort = $params['sort'];

		    if($action == "search") {
			    $data = searchPeople($query, $page, $limit, $sort);
		    }
		    else {
			    $data = browsePeople($page, $limit);
		    }

		    $status = 200;
		    break;

	    case "getVisualizations":
		    // Setup the default params
		    $params = array(
						    "page" => 1,
						    "limit" => 10,
						    "query" => "",
						    "sort" => "default",
						    "action" => "browse",
						    "type" => "people",
						);

		    // Check to see if values are set, overwrite defaults if set
		    foreach($params as $k => $v) {
			    if(isset($_REQUEST[$k])) {
				    $params[$k] = strtolower(safeString($_REQUEST[$k]));
			    }
		    }

		    $action = $params['action'];
		    $type = $params['type'];
		    $query = $params['query'];
		    $page = $params['page'];
		    $limit = $params['limit'];
		    $sort = $params['sort'];

		    if($action == "search") {
			    $data = searchVisualizations($query, $page, $limit, $sort);
		    }
		    else {
			    $data = browseVisualizationsByTimeCreated($page, $limit);
		    }   

		    $status = 200;
		    break;
		
		case "getSessions":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getSessionsForExperiment($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
		
		case "getExperimentFields":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getFields($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getExperimentVisualizations":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getVisByExperiment($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getExperimentTags":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getTagsForExperiment($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getExperimentVideos":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getVideosForExperiment($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getExperimentImages":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getImagesForExperiment($id);;
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;

		case "sessiondata":
			if(isset($_REQUEST['sessions'])) {
				
				$sessionIds = split(" ", $_REQUEST['sessions']);
				$dataset = array();
				
				foreach($sessionIds as $sid) {
					
					$eid = getSessionExperimentId($sid);
					$dataset[] = array('experimentId' => $eid, 
									'sessionId' => $sid, 
									'fields' => getFields($eid),
									'meta' => array(getSession($sid)),
									'data' => getData($eid, $sid));
				}
				
				$data = $dataset;
				$status = 200;
			}
			
			break;
		
		case "getExperiment":
			if(isset($_REQUEST['experiment'])) {
				
				$id = safeString($_REQUEST['experiment']);
				$dataset = getExperiment($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
		
		case "getUserProfile":
			if(isset($_REQUEST['user'])) {
				
				$id = safeString($_REQUEST['user']);
				$dataset = array();
				
				$exp = browseExperimentsByUser($id);
				$vid = getVideosByUser($id);
				$ses = browseMySessions($id);
				$img = getImagesByUser($id);
				$vis = getVisByUser($id);
				
				if(is_array($exp)) {
					$dataset['experiments'] = $exp;
				}
				
				if(is_array($vis)) {
					$dataset['vis'] = $vis;
				}
				 
				
				if(is_array($ses)) {
					$dataset['sessions'] = $ses;
				}
				
				if(is_array($img)) {
					$dataset['images'] = $img;
				}
				
				if(is_array($vid)) {
					$dataset['video'] = $vid;
				}
				
				$data = $dataset;
				$status = 200;
			}
			break;
			
		case "getExperimentByUser":
			if(isset($_REQUEST['user'])) {
				
				$id = safeString($_REQUEST['user']);
				$dataset = browseExperimentsByUser($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getVisByUser":
			if(isset($_REQUEST['user'])) {
				
				$id = safeString($_REQUEST['user']);
				$dataset = getVisByUser($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getSessionsByUser":
			if(isset($_REQUEST['user'])) {
				
				$id = safeString($_REQUEST['user']);
				$dataset = browseMySessions($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getImagesByUser":
			if(isset($_REQUEST['user'])) {
				
				$id = safeString($_REQUEST['user']);
				$dataset = getImagesByUser($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
		case "getVideosByUser":			
			if(isset($_REQUEST['user'])) {
				
				$id = safeString($_REQUEST['user']);
				$dataset = getVideosByUser($id);
				
				if($dataset) {
					$data = $dataset;
					$status = 200;
				}
			}
			break;
			
	    case "createSession":
            $session_key = (string)$_REQUEST['session_key'];
            $eid = (string)$_REQUEST['eid'];
            $name = (string)$_REQUEST['name']; 
            $description = (string)$_REQUEST['description']; 
            $street = (string)$_REQUEST['street'];
            $city = (string)$_REQUEST['city'];
            $country = (string)$_REQUEST['country']; 

            // Don't touch these, if you do I will burn down your house.
            $default_read = 1;
            $default_contribute = 1;
            $finalized = 1;

            $uid = getUserIdFromSessionToken($session_key);

            if(experimentClosed($_REQUEST['eid'])){
                $status=400;
                $data = array('msg'=>"Experiment Closed");
            } else {
                if($sid = createSession(array('uid' => $uid, 'session' => $session_key), $eid, $name, $description, $street, $city, $country, $default_read, $default_contribute, $finalized)) {
                    $status = 200;
                    $data = array('sessionId' => $sid ."");
                }
    		}

            break;
    	        	    
		case "putSessionData": 
	    case "updateSessionData":
	    
	        $params = array("sid", "eid", "session_key", "data");
	        $msg = "Hooray!";
	        $req = $_REQUEST;
	        $pass = true;
	        
	        foreach($params as $param) {
	            if(!isset($req[$param])) $pass = false; $msg = "Missing param {$param}"; break;
	        }
	    
	        if($pass) {
	            
	            $sid = (int)$_REQUEST['sid'];
    	        $eid = (int)$_REQUEST['eid'];
    	        $session_key = (string)$_REQUEST['session_key'];
                
    	        $proc_data = stripslashes(urldecode($_REQUEST['data']));
    	        $proc_data = json_decode($proc_data);	  

    	        if(experimentClosed($_REQUEST['eid'])){
                    $status=400;
                    $data = array('msg'=>"Experiment Closed");
    	        } else {
                    if(($count = putData($eid, $sid, $proc_data)) > 0) {
                        $data = array("msg" => "worked");
                        $status = 200;
                    }
                    else {
                        $data = array("msg" => "Data empty");
                        $status = 550;
                    }
    	        }
	            
	        }
	        else {
	            $data = array("msg" => $msg);
	            $status = 551;
	        }
	        
	        break;
	    
	    case "getDataSince":
	        $sid = (int) $_REQUEST['sid'];
	        $eid = (int) $_REQUEST['eid'];
	        $since = (int) $_REQUEST['since'];
	        
	        if($update = getDataSince($eid, $sid, $since)) {
	            $status = 200;
	            $data = array('sid' => $sid, 'update' => $update);
	        }
	        break;
	        
        	    case "uploadImageToExperiment":
        	        $file = (isset($_FILES['image']));
        	        $eid = (isset($_POST['eid']) ? $_POST['eid'] : null);
        	        $img_name = (isset($_POST['img_name']) ? $_POST['img_name'] : null);
        	        $img_desc = (isset($_POST['img_description']) ? $_POST['img_description'] : null);
        	        $session_key = (isset($_POST['session_key']) ? $_POST['session_key'] : null);

        	        if($eid == null) {
        	            $data = array("msg" => "Missing experiment parameter");
        	            $status = 551;
        	        }
        	        else if($img_name == null) {
        	            $data = array("msg" => "Missing name parameter");
        	            $status = 551;
        	        }
        	        else if($img_desc == null) {
        	            $data = array("msg" => "Missing description parameter");
        	            $status = 551;
        	        }
        	  //      else if($file == false) {
        	//            $data = array("msg" => "Missing image parameter");
        	 //           $status = 551;
        	//        }
        	        else if($session_key == null) {
        	            $data = array("msg" => "Missing session key parameter");
        	            $status = 551;
        	        }
        	        else {

        	            require_once LIB_DIR . 'S3.php';
        	            $s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
        	            $url = "http://s3.amazonaws.com/" . AWS_IMG_BUCKET;

        	            $target_path = '/tmp/';
        				$target_path = $target_path . basename($_FILES['image']['name']); 

        				// Mime Type Check
        				$mime = mime_content_type($_FILES['image']['tmp_name']);

        				$accepted_mimes = array(
        										'image/jpeg',
        										'image/gif',
        										'image/png',
        									);

        				if(!in_array($mime, $accepted_mimes)) {
        					$data = array("msg" => "The image type you attempted to upload is not supported");
        					$status = 552;

        					unlink($_FILES['image']['tmp_name']);
        				}
                        else {

                            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

                                $uid = getUserIdFromSessionToken($session_key);

        						$ext = substr($target_path, strpos($target_path, ".")+1);
        						$ext = str_replace(".", "", $ext);

        						$name = $eid . '_' . $uid . '_' . time() . '_1.' . $ext;
        						$s3->putObjectFile($target_path, AWS_IMG_BUCKET, $name, S3::ACL_PUBLIC_READ);
        						$provider_url = $url . '/' . $name;

        						createImageItem($session->userid, $eid, $img_name, $img_desc, 'Amazon S3', $name, $provider_url, AWS_IMG_BUCKET, 1);

        						$data = array("msg" => "Image upload successful!");
        						$status = 200;
        					}
        					else {
        					    unlink($_FILES['image']['tmp_name']);

        					    $data = array("msg" => "There was an error uploading your image");
        						$status = 553;
        					}
                        }
        	        }
        	        break;
        	        
        	        
        	            case "uploadImageToSession":
                	        $file = (isset($_FILES['image']));
                	        $eid = (isset($_POST['eid']) ? $_POST['eid'] : null);
                	        $sid = (isset($_POST['sid']) ? $_POST['sid'] : null);
                	        $img_name = (isset($_POST['img_name']) ? $_POST['img_name'] : null);
                	        $img_desc = (isset($_POST['img_description']) ? $_POST['img_description'] : null);
                	        $session_key = (isset($_POST['session_key']) ? $_POST['session_key'] : null);

                	        if($eid == null) {
                	            $data = array("msg" => "Missing experiment id parameter");
                	            $status = 551;
                	        }
							else if($sid == null) {
                	            $data = array("msg" => "Missing session id parameter");
                	            $status = 551;
                	        }
                	        else if($img_name == null) {
                	            $data = array("msg" => "Missing name parameter");
                	            $status = 551;
                	        }
                	        else if($img_desc == null) {
                	            $data = array("msg" => "Missing description parameter");
                	            $status = 551;
                	        }
                	  //      else if($file == false) {
                	//            $data = array("msg" => "Missing image parameter");
                	 //           $status = 551;
                	//        }
                	        else if($session_key == null) {
                	            $data = array("msg" => "Missing session key parameter");
                	            $status = 551;
                	        }
                	        else {

                	            require_once LIB_DIR . 'S3.php';
                	            $s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
                	            $url = "http://s3.amazonaws.com/" . AWS_IMG_BUCKET;

                	            $target_path = '/tmp/';
                				$target_path = $target_path . basename($_FILES['image']['name']); 

                				// Mime Type Check
                				$mime = mime_content_type($_FILES['image']['tmp_name']);

                				$accepted_mimes = array(
                										'image/jpeg',
                										'image/gif',
                										'image/png',
                									);

                				if(!in_array($mime, $accepted_mimes)) {
                					$data = array("msg" => "The image type you attempted to upload is not supported");
                					$status = 552;

                					unlink($_FILES['image']['tmp_name']);
                				}
                                else {

                                    if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

                                        $uid = getUserIdFromSessionToken($session_key);

                						$ext = substr($target_path, strpos($target_path, ".")+1);
                						$ext = str_replace(".", "", $ext);

                						$name = $sid . '_' . $uid . '_' . time() . '_1.' . $ext;
                						$s3->putObjectFile($target_path, AWS_IMG_BUCKET, $name, S3::ACL_PUBLIC_READ);
                						$provider_url = $url . '/' . $name;

                						createImageItemSes($session->userid, $eid, $sid, $img_name, $img_desc, 'Amazon S3', $name, $provider_url, AWS_IMG_BUCKET, 1);

                						$data = array("msg" => "Image upload successful!");
                						$status = 200;
                					}
                					else {
                					    unlink($_FILES['image']['tmp_name']);

                					    $data = array("msg" => "There was an error uploading your image");
                						$status = 553;
                					}
                                }
                	        }
                	        break;
	        
	    case "whatsMyIp":
	        $data = array("msg" => $_SERVER['REMOTE_ADDR']);
	        $status = 200;
	        break;
	        
	    case "getFileChecksum":
	        if(isset($_REQUEST['file']) !== FALSE) {
	            $filename = $_REQUEST['file'];
	            $data = array("checksum" => md5_file($filename));
	            $status = 200;
	        }
	        else {
	            $data = array("msg" => "Stop smoking drugs, that file doesn't exist you hippie");
	            $status = 500;
	        }
	        break;
	}
}

echo json_encode(array('status' => $status, 'data' => $data));

?>
