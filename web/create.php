<?php

require_once 'includes/config.php';

$type_units = getTypeUnits();
$types = getTypes();
$errors = array();
$created = false;
$values = array();
$eid = -1;

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
	
	if(count($errors) == 0) {
		if($exp = createExperiment($session->generateSessionToken(), $name, $desc, "")) {

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
