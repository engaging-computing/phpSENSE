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

$params = array('eid', 'uid', 'name', 'description', 'sessions');
$values = array();

$title = "Create Activity";
$aid = -1;

$done = false;

foreach($params as $param) {
    if(isset($_REQUEST[$param])) {
        $values[$param] = safeString($_REQUEST[$param]);
    }
}

if(!isset($values['uid'])) {
    $values['uid'] = $session->userid;
}

if(isset($_POST['activity_create'])) {
    $aid = createActivity($values['eid'], $values['sessions'], $values['uid'], $values['name'], $values['description']);
    $title = 'Activity Created!';
    $done = true;
}


$smarty->assign('values', $values);
$smarty->assign('done', $done);
$smarty->assign('aid', $aid);

$smarty->assign('user', $session->getUser());
$smarty->assign('title', $title);
$smarty->assign('content', $smarty->fetch('create-activity.tpl'));
$smarty->display('skeleton.tpl');

?>