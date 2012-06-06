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

function getTypeUnits() {
	global $db;
	
	$output = $db->query("	SELECT 	types.name AS `type_name`,
		 							types.type_id,
									units.name AS `unit_name`,
									units.unit_id
									FROM type_units, types, units
									WHERE types.type_id = type_units.type_id
									AND units.unit_id = type_units.unit_id");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getTypes() {
	global $db;
	
	$output = $db->query("SELECT types.type_id, types.name FROM types ORDER BY types.name ASC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getUnits() {
	global $db;
	
	$output = $db->query("SELECT units.unit_id, units.name FROM units ORDER BY units.name ASC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getTypeIds($tid) {
	global $db;
	
	$output =  $db->query("SELECT	units.unit_id,
									units.name,
									units.abbreviation
									FROM units, type_units
									WHERE type_units.type_id = {$tid}
									AND units.unit_id = type_units.unit_id 
									ORDER BY units.name ASC");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;	
}

function getActiveUsers() {
	global $db;
	
	$output = $db->query("SELECT 	tokens.user_id, 
									users.firstname, 
									users.lastname, 
									tokens.updated FROM tokens 
									LEFT JOIN ( users ) ON ( users.user_id = tokens.user_id ) 
									WHERE tokens.updated + 1800 > NOW()");
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function safeString($string) {
	if(get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	
	return str_replace("`", "\`", mysql_real_escape_string($string));
}

function contrib_cmp($a, $b) {
	if($a['contrib_count'] == $b['contrib_count']) {
		return 0;
	}
	
	return ($a['contrib_count'] < $b['contrib_count']);
}

function session_cmp($a, $b) {
	if($a['session_count'] == $b['session_count']) {
		return 0;
	}
	
	return ($a['session_count'] > $b['session_count']);
}

function date_cmp($a, $b) {
	
	if($a['timecreated'] == $b['timecreated']) {
		return 0;
	}
	
	return ($a['timecreated'] < $b['timecreated']);
}

function timeobj_cmp($a, $b) {
	
	if($a['timeobj'] == $b['timeobj']) {
		return 0;
	}
	
	return ($a['timeobj'] < $b['timeobj']);
}

function sort_relevancy($a, $b) {
	if($a['relevancy'] == $b['relevancy']) {
		return 0;
	}
	
	return ($a['relevancy'] < $b['relevancy']);
}

function sort_exp_popularity($a, $b) {
	if($a['contrib_count'] == $b['contrib_count']) {
		return 0;
	}
	
	return ($a['contrib_count'] < $b['contrib_count']);
}

function sort_exp_activity($a, $b) {
	if($a['session_count'] == $b['session_count']) {
		return 0;
	}
	
	return ($a['session_count'] < $b['session_count']);
}

function getVersionNumber(){
    return exec('git describe --tags');
}

function dateDifference($day_1, $day_2) {
	$diff = $day_1 - $day_2;

	$sec   = $diff % 60;
	$diff  = intval($diff / 60);
	$min   = $diff % 60;
	$diff  = intval($diff / 60);
	$hours = $diff % 24;
	$days  = intval($diff / 24);
	
	$date_diff_string = "";
	if($days != 0) {
		$date_diff_string .= $days . " days ";
	}
	
	if($hours != 0) {
		$date_diff_string .= $hours . " hours ";
	}
	
	if($min != 0) {
		$date_diff_string .= $min . " minutes ";
	}
	
	$date_diff_string .= "ago";

	return $date_diff_string;
}

?>
