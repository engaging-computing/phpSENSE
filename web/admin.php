<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
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
 -->
<?php

require_once 'includes/config.php';
require_once API_DIR . 'admin.php';
require_once LIB_DIR . 'S3.php';

$action = "";
if(isset($_GET['action'])) { $action = safeString($_GET['action']); }
if(isset($_POST['action'])) { $action = safeString($_POST['action']); }

$done = false;
$data = array();
$errors = array();
$title = 'iSENSE Management';
$action_template = 'admin/index.tpl';

$tmpusr = $session->getUser();

if($tmpusr['administrator'] == 1)
switch($action) {
	
	case "fixvisualizationexp":
		$sql = "SELECT * FROM visualizationSessionMap";
		$maps = $db->query($sql);
		
		foreach($maps as $map) {
			
			$sid = $map['session_id'];
			$vid = $map['vis_id'];
			
			$eid = getSessionExperimentId($sid);
			
			$sqlUp = "UPDATE visualizations SET experiment_id = '{$eid}' WHERE vis_id = '{$vid}'";
			$r = $db->query($sqlUp);
		}
	
		break;
	
	case "fixsessionescape":
		$sql = "SELECT sessions.session_id, sessions.name, sessions.description FROM sessions";
		$data = $db->query($sql);
		$fixes = array();
		
		foreach($data as $datum) {
			$sid = $datum['session_id'];
			$title = $datum['name'];
			$desc = $datum['description'];
			
			if(strpos($title, "\\") !== FALSE || strpos($desc, "\\") !== FALSE) {
				
				while (strstr($title, '\\')) {
					$title = stripslashes($title);
				}
				
				while (strstr($desc, '\\')) {
					$desc = stripslashes($desc);
				}
								
				$fixes[] = array("sid" => $sid, "title" => $title, "desc" => $desc);				
			}
		}
		
		foreach($fixes as $fix) {
			
			$sid = safeString($fix['sid']);
			$desc = safeString($fix['desc']);
			$title = safeString($fix['title']);
			
			$sql = "UPDATE sessions SET sessions.name = '{$title}', sessions.description = '{$desc}' WHERE sessions.session_id = '{$sid}'";
			$r = $db->query($sql);
		}

		break;
	
	case "migratetags":
		$sqlUniqueTags = "SELECT tags.tag FROM tags WHERE tags.tag != '' GROUP BY tags.tag";
		$uniqueTags = $db->query($sqlUniqueTags);
		$uniqueTagCount = $db->numOfRows;
		
		$count = 0;
		foreach($uniqueTags as $utag) {
			$tag = $utag['tag'];
			if(strlen($tag) > 1) {
				$sqlTagIndex = "INSERT INTO tagIndex (`value`, `weight`) VALUES('{$tag}', '1')";
				$db->query($sqlTagIndex);
				$count++;
			}
		}
		
		echo "{$uniqueTagCount}, {$count}<br/>";
		
		$sqlExperiments = "SELECT tags.tag, tags.experiment_id FROM tags";
		$experiments = $db->query($sqlExperiments);
		$experimentCount = $db->numOfRows;
		
		$ecount = 0;
		foreach($experiments as $exp) {
			$tag = $exp['tag'];
			$eid = $exp['experiment_id'];
			$tid = getTagId($tag);
			if($tid != -1) {
				addTagToExperiment($eid, $tid, 2);
				$ecount++;
			}
		}
		
		echo "{$experimentCount}, {$ecount}<br/>";
		
		$sqlExperiments = "SELECT experiments.experiment_id, experiments.name, experiments.description FROM experiments";
		$experiments = $db->query($sqlExperiments);
		$experimentCount = $db->numOfRows;
		
		foreach($experiments as $exp) {
			$eid = $exp['experiment_id'];
			$name = preg_split("[\s+]", $exp['name']);
			$desc = preg_split("[\s+]", $exp['description']);
			
			foreach($name as $n) {
				if(strlen($n) > 1) {
					$tid = getTagId(safeString($n));
					if($tid == -1) {
						$tid = addTag(safeString($n), 1);
					}
					
					addTagToExperiment($eid, $tid, 1);
				}
			}
			
			foreach($desc as $n) {
				if(strlen($n) > 1) {
					$tid = getTagId(safeString($n));
					if($tid == -1) {
						$tid = addTag(safeString($n), 1);	
					}
					
					addTagToExperiment($eid, $tid, 1);
				}
			}
		}
		
		break;
	
	case "migrateimgs":
		$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);
		$url = "http://s3.amazonaws.com/isenseimgs/";
		$img_dir = PIC_DIR . 's3/';
		$dir = opendir($img_dir);
		
		while (($file = readdir($dir)) !== false) {
			
			$id = substr($file, 0, strpos($file, ".")+1);
			$ext = substr($file, strpos($file, ".")+1, 3);
			
			if(strlen($ext) < 3) {
				continue;
			}
			
			$img = $db->query("SELECT * FROM pictures WHERE provider_id = {$id}");
			
			$pid = $img[0]['picture_id'];
			
			$uri =  $pid . '.' . $ext;
			
			$name = $img_dir . '' . $file;

			print_r($s3->putObjectFile($name, AWS_IMG_BUCKET, $uri, S3::ACL_PUBLIC_READ));
			
			$db->query("UPDATE pictures SET provider = 'Amazon S3', provider_group_id = 'isenseimgs', provider_id = 'isenseimgs/{$uri}', provider_url = '{$url}{$uri}' WHERE picture_id = {$pid}");
			
			echo $uri . '<br/>';
		}
		break;
	
	/* Event Actions */
	case "eventadd":
		$title = 'Create New Event';
		$action_template = 'admin/event-add.tpl';
		
		if(isset($_POST['create'])) {
			$etitle = "";
			if(isset($_POST['title'])) { $etitle = safeString($_POST['title']); }
			if($etitle == "") { array_push($errors, 'Title can not be blank'); }
			
			$description = "";
			if(isset($_POST['description'])) { $description = safeString($_POST['description']); }
			if($description == "") { array_push($errors, 'Description can not be blank'); }
			
			$location = "";
			if(isset($_POST['location'])) { $location = safeString($_POST['location']); }
			if($location == "") { array_push($errors, 'Location can not be blank.'); }
			
			$start = "";
			if(isset($_POST['start'])) { $start = safeString($_POST['start']);  } 
			if($start == "") { array_push($errors, 'Start date can not be blank.'); }
			
			$end = "";
			if(isset($_POST['end'])) { $end = safeString($_POST['end']); }
			if($end == "") { array_push($errors, 'End date can not be blank.'); }
			
			if(count($errors) == 0) {
				createEvent($session->generateSessionToken(), $etitle, $description, $location, $start, $end);
				$title = 'Successfully Created New Event';
				$done = true;
			}
		}
		
		break;
	
	case "eventmanage":
		$title = 'Manage Events';
		$data = adminGetEvents();
		$action_template = 'admin/event-manage.tpl';
		break;

	case "deleteevent":
		$names = explode(":", $_GET['names']);
		foreach( $names as $name ) {
			eventDelete($name);
		}
		break;
	
	/* News Actions */
	case "newsadd":
		$title = 'Create New Article';
		$action_template = 'admin/news-add.tpl';
		
		if(isset($_POST['create'])) {
			$etitle = "";
			if(isset($_POST['title'])) { $etitle = safeString($_POST['title']); }
			if($etitle == "") { array_push($errors, 'Title can not be blank'); }
			
			$description = "";
			if(isset($_POST['content'])) { $description = safeString($_POST['content']); }
			if($description == "") { array_push($errors, 'Content can not be blank'); }
			
			if(count($errors) == 0) {
				createNewsItem($session->generateSessionToken(), $etitle, $description, $publish = 1); 
				$title = 'Successfully Created New Article';
				$done = true;
			}
		}
		
		break;
	
	case "newsmanage":
		$title = 'Manage News';
		$data = adminGetNews();
		$action_template = 'admin/news-manage.tpl';
		break;

	case "newspublish":
		$nids = explode(":", $_GET['nids']);
		foreach( $nids as $nid )
			newsPublish($nid);
	
		break;

	case "deletenews":
		$nids = explode(":", $_GET['nid']);
		foreach( $nids as $nid ) 
			newsDelete($nid);
		
		break;
	
	/* Experiment Actions */
	case "experimentsmanage":
		$title = 'Manage Experiments';
		$data = adminGetExperiments();
		$action_template = 'admin/experiment-manage.tpl';
		break;
		
	case "deleteexperiment":
		$eids = explode(":", $_GET['eid']);
		foreach( $eids as $eid ) {
			experimentDelete($eid);
		}
		break;

	case "featureexperiment":
		$eids = explode(":", $_GET['eid']);
		foreach( $eids as $eid )
			experimentFeature( $eid );

		break;

	/* User Actions */
	case "usermanage":
		$title = 'Manage Users';
		$data = adminGetUsers();
		$action_template = 'admin/user-manage.tpl';
		break;
	
	case "usermassmail":

		if( isset($_POST['subject']) )
		  if( $_POST['subject'] != "" ){
		    if( $_POST['message'] != "" ){

		      $sql = "SELECT email FROM users WHERE hideemail=0";
		      $addresses = $db->query($sql);
		      $subject = $_POST['subject'];
		      $message = $_POST['message'];
		      $header = 'From: iSENSEteam@isense.cs.uml.edu' . "\r\n";
		      $to = "";


              $message .= '<br /><br />For questions and concerns please contact our founder <a href="mailto:fgmartin13@gmail.com">Here</a><br />To remove yourself from our mailing list please click <a href="http://isense.cs.uml.edu/remove-email.php">Here</a>';

            foreach( $addresses as $address ) {
			  if( $address['email'] != "" )
			    $to .= $address['email'] . '\r\n';
		      }

		      mail( $to, $subject, $message, $header );

		    } else { $error = "Message Blank"; }		
		  } else { $error = "Subject Blank"; }
		

		$title = 'User Mass Mailing';
		$action_template = 'admin/user-massmail.tpl';

		break;

	case "deleteuser":
		$uids = explode(":", $_GET['uid']);
		foreach( $uids as $uid ) {
			userDelete($uid);
		}
		break;

	case "makeadmin":
		$uids = explode(":", $_GET['uid']);
		foreach( $uids as $uid )
			userAdmin($uid);

		break;
		
	case "helpmanage":
		$title = 'Manage Help Articles';
		$data = adminGetHelpArticles();
		$action_template = 'admin/help-manage.tpl';
		break;
	
	case "helpadd":
		$title = 'Add New Help Article';
		$action_template = 'admin/help-add.tpl';
		
		if(isset($_POST['create'])) {
			
			$published = 0;
			if(isset($_POST['publish']) && $_POST['publish'] == "Yes") {
				$published = 1;
			}
			
			
			$etitle = "";
			if(isset($_POST['title'])) { $etitle = safeString($_POST['title']); }
			if($etitle == "") { array_push($errors, 'Help topic can not be blank'); }
			
			$description = "";
			if(isset($_POST['content'])) { $description = safeString($_POST['content']); }
			if($description == "") { array_push($errors, 'Help response can not be blank'); }
			
			if(count($errors) == 0) {
				createSupportArticleItem($session->generateSessionToken(), $etitle, $description, 0, $published);
				$title = 'Successfully Created New Article';
				$done = true;
			}
		}
		
		break;

	case "deletehelp":
		$hids = explode(":", $_GET['hids']);
		foreach( $hids as $hid ) {
			helpDelete($hid);
		}
		break;

	case "helppublish":
		$hids = explode(":", $_GET['hids']);
		foreach( $hids as $hid )
			helpPublish($hid);
	
		break;
	
	case "faqmanage":
		$title = 'Manage FAQs';
		$data = adminGetFaqs();
		$action_template = 'admin/faq-manage.tpl';
		break;
	
	case "faqadd":
		$title = 'Add FAQ';
		$action_template = 'admin/faq-add.tpl';
		
		if(isset($_POST['create'])) {
			$etitle = "";
			if(isset($_POST['title'])) { $etitle = safeString($_POST['title']); }
			if($etitle == "") { array_push($errors, 'FAQ can not be blank'); }
			
			$description = "";
			if(isset($_POST['content'])) { $description = safeString($_POST['content']); }
			if($description == "") { array_push($errors, 'FAQ Answer can not be blank'); }
			
			if(count($errors) == 0) {
				createSupportArticleItem($session->generateSessionToken(), $etitle, $description, 1); 
				$title = 'Successfully Created New Article';
				$done = true;
			}
		}
		
		break;

	case "deletefaq":
		$fids = explode(":", $_GET['fids']);
		foreach( $fids as $fid ) {
			helpDelete($fid);
		}
		break;

	case "faqpublish":
		$fids = explode(":", $_GET['fids']);
		foreach( $fids as $fid )
			helpPublish($fid);
	
		break;

	case "passreset":
		$uids = explode(":", $_GET['uids']);
		foreach( $uids as $uid )
			resetPass($uid);
	
		break;
		
	case "migratetime":
	
	    $exp = getAllExperiments();
	    $eresults = array();
	    
	    $counts = array(
	        "total" => 0,
	        "Human Readable" => 0,
	        "Human Readable - Unparsable" => 0,
	        "Can not find time field" => 0,
	        "Not Human Readable" => 0,
	        "Unknown" => 0,
	        "No Sessions Found" => 0,
	        "Experiment Does Not Use Time" => 0
	    );
	    
	    foreach($exp as $e) {
	        $eid = $e['experiment_id'];
	        
	        // echo $eid . "<br/>";
	        
	        if(($field_name = experimentHasTime($eid)) !== FALSE) {
	            $session_types = array();
	            $sessions = getSessionsForExperiment($eid);
	            
	            if($sessions != FALSE) {
	                //foreach($sessions as $session) {
    	            for($i = 0; $i < count($sessions); $i++) {
    	                $session = $sessions[$i];
    	                
    	                $field_name = strtolower($field_name);
    	                $results = $mdb->find("e{$eid}", array("session" => (int) $session['session_id']), array($field_name => 1));

        	            $time_fail = false;
        	            $type = "Unknown";

        	            foreach($results as $result) {
        	                if(isset($result[$field_name])) {
        	                    $val = $result[$field_name];
        	                }
        	                else if(isset($result[ucwords($field_name)])) {
        	                    $val = $result[ucwords($field_name)];
        	                }
        	                else {
        	                    $val = null;
        	                }

        	                if($val != NULL) {
        	                    // Check to see if there are words in the val
                			    if((preg_match('/[a-z0-9]+/i', $val) != 0) || strpos($val, "/") !== FALSE) {

                		            // Try and parse the ridiculous date format, and hope to god it works
                		            if(($new_time = strtotime($val)) !== FALSE && $time_fail == FALSE) {
                		                $type = "Human Readable";
                		            }
                		            else {
                		                // If not assume incremental seconds from upload
                		                $type = "Human Readable - Unparsable";
                		            }
                		        }
                		        else {
                		            $type = "Not Human Readable";
                		        }
        	                }
        	                else {
        	                    $type = "Can not find time field";
        	                }
        	                
        	                break;
        	            }
        	            
    	                $session_types[$session['session_id']] = $type;
        	            $counts['total']++;
        	            $counts[$type]++;
    	            }

    	            $eid = "e{$eid}";
    	            $eresults[$eid] = $session_types;
	            }
	            else {
	                $eid = "e{$eid}";
	                $eresults[$eid] = array("-1" => "No Sessions Found");
	                $counts["No Sessions Found"]++;
	            }
	        }
	        else {
	            $eid = "e{$eid}";
                $eresults[$eid] = array("-1" => "Experiment does not use time");
                $counts["Experiment Does Not Use Time"]++;
	        }
	    }
	    
	    echo "<table>";
        foreach($counts as $k => $v) echo "<tr><td>{$k}</td><td>{$v}</td></tr>";
        echo "</table>";
        
        
        echo "<table>";
        foreach($eresults as $eid => $sessions) {
            
            echo "<tr><td>Experiment: {$eid}</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            
            foreach($sessions as $k => $v) {
                echo "<tr><td>&nbsp;</td><td>{$k}</td><td>{$v}</td>";
            }
        }
        echo "</table>";
	
	    break;
		
	default:
		$action_template = 'admin/index.tpl';
		break;
}
else
header('Location: http://isense.cs.uml.edu');

if($data === false) {
	$data = array();
}

$smarty->assign('errors', $errors);
$smarty->assign('done', $done);
$smarty->assign('data', $data);
$smarty->assign('title', $title);
$smarty->assign('user', $session->getUser());
$smarty->assign('head', $smarty->fetch('parts/admin-head.tpl'));
$smarty->assign('content', $smarty->fetch($action_template));
$smarty->display('skeleton.tpl');

?>
