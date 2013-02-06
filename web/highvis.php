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
if(isset($_REQUEST['sessions']) || isset($_REQUEST['vid'])) {

    $sessions = Array();

    if(isset($_REQUEST['sessions'])){
        
        $sessions = explode(' ', $_REQUEST['sessions']);
    
        //Call data.php and return the JS Data object
        $head = '<script type="text/javascript" src="/ws/highdata.php?sessions=';
        
        //Append the session id for each session requested
        foreach($sessions as $ses)
            $head .= $ses . '+';

        //Strip the final '+'
        $head = substr($head, 0, -1);
        
        //Close the script
        $head .= '"></script>';
    
    } else if(isset($_REQUEST['vid'])){
        
        $head = '<script type="text/javascript" src="/ws/highdata.php?vid='.$_REQUEST['vid'].'"></script>';
        
    }

    $head .= '<script type="text/javascript" src="/html/js/lib/jquery.mousewheel.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/lib/jquery.mCustomScrollbar.js"></script>';
    //$head .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=visualization&sensor=false"></script>';
    $head .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.9&libraries=visualization&sensor=false"></script>';
    $head .= '<script type="text/javascript" src="/html/js/lib/StyledMarker.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/jquery.dataTables.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/lib/jquery.prettyPhoto.js"></script>';

    $head .= '<script type="text/javascript" src="/html/js/highvis/highcharts/highcharts.js"></script>';

    $head .= '<script type="text/javascript" src="/html/js/lib/Hydrate.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/savedVis.js"></script>';

    $head .= '<script type="text/javascript" src="/html/js/highvis/visUtils.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/dataReduction.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highmodifiers.js"></script>';

    $head .= '<script type="text/javascript" src="/html/js/highvis/baseVis.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/disabledVis.js"></script>';
    
    $head .= '<script type="text/javascript" src="/html/js/highvis/photos.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/scatter.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/timeline.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/histogram.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/bar.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/table.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/map.js"></script>';
    $head .= '<script type="text/javascript" src="/html/js/highvis/highcharts/modules/exporting.js"></script>';
    
    $head .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
    $head .= '<script>google.load("visualization", "1", {"packages":["motionchart"]});</script>';
    
    $head .= '<script type="text/javascript" src="/html/js/highvis/motion.js"></script>';
    
    $head .= '<script type="text/javascript" src="/html/js/highvis/runtime.js"></script>';

    $head .= '<link rel="stylesheet" type="text/css" href="/html/css/jquery-ui.css"></link>';
    $head .= '<link rel="stylesheet" type="text/css" href="/html/css/jquery.dataTables.css"></link>';
    $head .= '<link rel="stylesheet" type="text/css" href="/html/css/mCustomScrollbar.css"></link>';
    $head .= '<link rel="stylesheet" type="text/css" href="/html/css/demo_table.css"></link>';
    $head .= '<link rel="stylesheet" type="text/css" href="/html/css/highvis.css"></link>';
    $head .= '<link rel="stylesheet" type="text/css" href="/html/css/prettyPhoto.css" media="screen"></link>';

    $smarty->assign('head', $head);
}
 
// If there is only one session the title should include it.
if (isset($_REQUEST['sessions'])){
    if (count($sessions) == 1){
        $session_data = getSession($sessions[0]);
        $session_name = $session_data['name'];
        $name = getExperimentNameFromSession($sessions[0]);
        $edit = ' <a style="font-size:.8em;" href="session-edit.php?id=' . $sessions[0] . '">[edit]</a>';
        $link = '<a href="experiment.php?id='. $name['experiment_id'].'">'.$name['name'] . '</a> > ' . $session_name . $edit;
    } else {
        $name = getExperimentNameFromSession($sessions[0]);
        $link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a>';
    }
}
else if (isset($_REQUEST['vid'])){
    $vis_desc = getSavedVisDesc($_REQUEST['vid']);

    $name = getExperimentNameFromVisualization($_REQUEST['vid']);
    $link = '<a href="experiment.php?id='.$name['experiment_id'].'">'.$name['name'].'</a> > '. $vis_desc['title'];
}

$smarty->assign('link', $link);
$smarty->assign('title', $name['name']);
$smarty->assign('errors', $errors);
$smarty->assign('user', $session->getUser());

$smarty->assign('content', $smarty->fetch('highvis.tpl'));
$smarty->display('skeleton.tpl');

?>
