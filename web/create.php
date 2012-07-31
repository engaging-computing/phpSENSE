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

$type_units = getTypeUnits();
$types = getTypes();
$errors = array();
$created = false;
$values = array();
$eid = -1;
$reqs = array();

$reqs[] = 

$smarty->assign('created', false);

if(isset($_POST['experiment_create'])) {
	
	$name = "";
	if(isset($_POST['experiment_name'])) { $name = safeString($_POST['experiment_name']); }
	if($name == "") { array_push($errors, 'Experiment name can not be blank.'); }
	$values['name'] = $name;
	
	$desc = "";
	if(isset($_POST['experiment_description'])) { $desc = safeString($_POST['experiment_description']); }
	if($desc == "") { array_push($errors, 'Experiment description can not be blank.'); }
	$values['description'] = $desc;
	
	$fields = array();
	if(isset($_POST['fields'])) { $fields = safeString($_POST['fields']); }
        if($fields == null) { array_push($errors, 'Experiment fields can not be blank.'); }
        $fields['fields'] = $fields;
	
	
	if(isset($_POST['req_procedure'])) { $req_procedure=$_POST['req_procedure']; }
	if(isset($_POST['req_location'])) { $req_location=$_POST['req_location']; }
	if(isset($_POST['name_prefix'])) { $name_prefix=$_POST['name_prefix']; }
    if(isset($_POST['req_name'])) { $req_name=safeString($_POST['req_name']); }
	if(isset($_POST['location'])) { $location=safeString($_POST['location']); }

	
	if(count($errors) == 0) {
		if($exp = createExperiment($session->generateSessionToken(), $name, $desc, "", $req_name, $req_procedure, $req_location, $name_prefix, $location)) {

			$tag_list = array();

			// Add tags from description and title
			$auto_added = array();
			$auto_tags = explode(' ', $name . ' ' . $desc);
			foreach($auto_tags as $t) {
				if(!in_array($t, $auto_added)) {
					$val = safeString(str_replace(",", "", $t));
					if(strlen($val) > 0) {
						$tag_list[] = array('value' => $val, 'weight' => 1);
					}
				}
			}
			
			
			// Add user specified tags to experiment
			$user_tags = explode(' ', safeString($_POST['experiment_tags']));
			foreach($user_tags as $t) {
				$val = safeString(str_replace(",", "", $t));
				if(strlen($val) > 0) {
					$tag_list[] = array('value' => $val, 'weight' => 2);
				}
			}
			
			// Add user specified subject tags to experiment
			$add_tags = array('math' => 'Mathematics', 'phys' => 'Physics', 'chem' => 'Chemistry', 'bio' => 'Biology', 'earth' => 'Earth Science');
			foreach($add_tags as $k => $t) {
				$name = 'add_tag_' . $k;
				if($_POST[$name] == 'yes') {
					$val = safeString(str_replace(",", "", $t));
					if(strlen($val) > 0) {
						$tag_list[] = array('value' => $val, 'weight' => 2);
					}
				}
			}
		
			// Push experiment tags to the db
			addTagsToExperiment($exp['experiment_id'], $tag_list);

			// Create empty session with propper fields
			$session_id = createSession($session->generateSessionToken(), $exp['experiment_id']);

			// Add fields to empty session
			$limit = (int) safeString($_POST['number_of_fields']);
			$limit += 1;
			for($i = 1; $i < $limit; $i++) {
				$name = 'field_label_' . $i;
				$type = 'field_type_' . $i;
				$unit = 'field_unit_' . $i;
	

				if(isset($_POST[$name]) && isset($_POST[$type])) {
					$fieldName = safeString($_POST[$name]);
					$fieldType = safeString($_POST[$type]);
					$fieldUnit = safeString($_POST[$unit]);

					if( $fieldType == 7 && $fieldUnit != 28 ) {
					    $fieldName = 'Time';
					    $fieldUnit = 28;
					}

					if($_POST[$name] != "") {
						addfieldToSession($session->generateSessionToken(), $session_id, str_replace("_", " ", $fieldName), $fieldType, $fieldUnit);
					}
				}
			}
			
			$created = true;
			$eid = $exp['experiment_id'];
		}
	}
}

$defaultSensors = array( 0 => "temperature", 1 => "light", 2 => "acceleration", 3 => "altitude", 4 => "preasure", 5 => "humidity" );
$smarty->assign('defaultSensors', $defaultSensors);

$smarty->assign('head', '<script src="/html/js/lib/jquery.validate.js"></script>' . 
						'<script src="/html/js/lib/validate.js"></script>'.
						'<script src="/html/js/create.js"></script>');

$smarty->assign('values', $values);
$smarty->assign('marker', 'create');
$smarty->assign('created', $created);
$smarty->assign('errors', $errors);
$smarty->assign('eid', $eid);
$smarty->assign('type_units', $type_units);
$smarty->assign('types', $types);

$smarty->assign('user', $session->getUser());
$smarty->assign('title', 'Create Experiment');
$smarty->assign('content', $smarty->fetch('create.tpl'));
$smarty->display('skeleton.tpl');

?>
