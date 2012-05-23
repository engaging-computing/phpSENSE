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

$errors = array();
$results = array();
$count = 0;
$next = false;

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

// Determine the results
if($action == "search") {
	
	if($type == "visualizations") {
		$results = searchVisualizations($query, $page, $limit, $sort);
		$count = searchVisualizations($query, -1, $limit, $sort);
	}
	else if($type == "people") {
		$results = searchPeople($query, $page, $limit, $sort);
		$count = searchPeople($query, -1, $limit, $sort);
	}
	/*
	else if($type == "activities") {
	    $result = searchActivities($query, $page, $limit, $sort);
	    $count = searchPeople($query, -1, $limit, $sort);
	}
	*/
	else {
		$results = searchExperiments($query, $page, $limit, $sort);
		$count = count($results);
        
	}
	
}
else if($action == "browse") {
   
	if($type == "visualizations") {
		$results = browseVisualizationsByTimeCreated($page, $limit);
		$count = browseVisualizationsByTimeCreated(-1, $limit);
	}
	else if($type == "people") {
		$results = browsePeople($page, $limit);
		$count = browsePeople(-1, $limit);
	}
	else if($type == "activities") {
	    $results = browseActivities($page, $limit);
	    $count = browseActivities(-1, $limit);
	}
	else {

		if($sort == "default" || $sort == "recent") {
			$results = browseExperimentsByRecent($page, $limit);
			$count = browseExperimentsByRecent(-1, $limit);
		}
		else if($sort == "popularity") {
			$results = browseExperimentsByPopular($page, $limit);
			$count = browseExperimentsByPopular(-1, $limit);
		}
		else if($sort == "activity") {
			$results = browseExperimentsByActivity($page, $limit);
			$count = browseExperimentsByActivity(-1, $limit);
		}
		else if($sort == "rating") {
			$results = browseExperimentsByRating($page, $limit);
			$count = browseExperimentsByRating(-1, $limit);
		}
	}
}

// Determine sort text
$sorttext = "by the date each was created";
if($sort == "popularity") {
	$sorttext = "by the number of contributors each contains";
}
else if($sort == "activity") {
	$sorttext = "by the number of sessions each has";
}
else if($sort == "rating") {
	$sorttext = "by each experiments user rating on a five-point scale";
}
else if($type == "people") {
    $sorttext = "alphabetically by last name, then first name";
}

// Package the params as 
foreach($params as $k => $v) {
	$smarty->assign($k, $v);
}

$pages = round(($count / $limit), 2);
$pages_mod = ($count % $limit);
$next = $page < $pages;
$numpages = ceil( $pages );

// Generate navbar data
$navbarpages = array();

for( $i = 1; $i < 10; $i++ ) {

	if( $page + $i - 4 > 0 && $page + $i - 4 <= $numpages ) {

		$navbarpages[$i] = $page + $i - 4;

	}

}

$smarty->assign('head', '<script src="/html/js/hideexp.js"></script>');

// Assign params to Smarty
$smarty->assign('title',		ucwords($action . ' ' . $type));
$smarty->assign('marker',		$type);
$smarty->assign('errors',		$errors);
$smarty->assign('params',		$params);
$smarty->assign('results',		$results);
$smarty->assign('sorttext',		$sorttext);
$smarty->assign('next',			$next);
$smarty->assign('navbarpages',		$navbarpages);
$smarty->assign('numpages',		$numpages);

$smarty->assign('user', 		$session->getUser());
$smarty->assign('content', 		$smarty->fetch('browse.tpl'));
$smarty->display('skeleton.tpl');

?>
