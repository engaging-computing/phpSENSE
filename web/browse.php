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
$tmp = array("water", "quality");

//theRealDeal(0,"off","off",$tmp,"rating");

// Setup the default params
$params = array(
                "page" => 1,
                "limit" => 10,
                "query" => "",
                "sort" => "default",
                "action" => "browse",
                "type" => "experiments",
                "recommended" => "off",
                "sorttype" => "recent",
                "featured" => "off"
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
$recommended = $params['recommended'];
$featured = $params['featured'];
$sorttype = $params['sorttype'];

if($type=="experiments"){
    $response = getExperiments($page, $limit,0, $featured, $recommended, $query, $sorttype);
    $results = $response['data'];
    $count = $response['count'];  
} elseif ($type == "people") {
    $response = getPeople($page, $limit,$query);
    $results = $response['data'];
    $count = $response['count'];  
} elseif ($type == "visualizations"){
    $response = getVisualizations($query, $page, $limit, $sort);
    $results = $response['data'];
    $count = $response['count'];  
}

// Determine sort text
$sorttext = "by when they were last modified";
if($sorttype == "popularity") {
    $sorttext = "by the number of contributors each contains";
}
else if($sorttype == "activity") {
    $sorttext = "by the number of sessions each has";
}
else if($sorttype == "rating") {
    $sorttext = "by each experiment's user rating on a five-point scale";
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


// Assign params to Smarty
$smarty->assign('title',        $type);
$smarty->assign('marker',       $type);
$smarty->assign('errors',       $errors);
$smarty->assign('params',       $params);
$smarty->assign('results',      $results);
$smarty->assign('sorttext',     $sorttext);
$smarty->assign('next',         $next);
$smarty->assign('navbarpages',  $navbarpages);
$smarty->assign('numpages',     $numpages);
$smarty->assign('sorttype',     $sorttype);
$smarty->assign('user',         $session->getUser());
$smarty->assign('content',      $smarty->fetch('browse.tpl'));
$smarty->display('skeleton.tpl');

?>
