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

/*
 *$smarty->assign('aid', $aid);
 *$smarty->assign('vis', $vis);
 *$smarty->assign('is_saved', $is_saved);
 *$smarty->assign('activity', $is_activity);
 *$smarty->assign('activity_meta', $activity_meta);
 *
 *$smarty->assign('link', $link);
 *$smarty->assign('title', $name['name']);
 *$smarty->assign('errors', $errors);
 *
 *$smarty->assign('user', $session->getUser());
 *
 *if(FLOT_ENABLED == TRUE) {
 *   $smarty->assign('head', $smarty->fetch('parts/vis-head-flot.tpl'));
 *}
 *else {
 *   $smarty->assign('head', $smarty->fetch('parts/vis-head.tpl'));
 *}
 */

// if newviz.php gets an arguement called sessions
if(isset($_REQUEST['sessions'])) {
    $sessions = explode(' ', $_REQUEST['sessions']);
    
    //Call data.php and return the JS Data object
    $head = '<script type="text/javascript" src="/ws/data.php?sessions=';
    
    //Append the session id for each session requested
    foreach($sessions as $ses)
        $head .= $ses . '+';
        
    //Strip the final '+'
    $head = substr($head, 0, -1);

    //Close the script
    $head .= '"></script>';
    
    $head .= '<script type="text/javascript" src="/html/js/modifiers.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/timeline.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/scatter.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/histogram.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/bar.js"></script>';
    $head .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/map.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/table.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/newvis/runtime.js"></script>';
    
    $smarty->assign('head', $head);
}
        

 
$smarty->assign('title', 'New Viz');
$smarty->assign('errors', $errors);

$smarty->assign('content', $smarty->fetch('newvis.tpl'));
$smarty->display('skeleton.tpl');

?>
