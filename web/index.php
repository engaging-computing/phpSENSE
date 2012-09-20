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

function sort_news_and_events($a, $b)
{
    if ($a['date'] == $b['date']) {
        return 0;
    }
    return ($a['date'] < $b['date']) ? 1 : -1;
}

$news = getNews();
$events = getEvents();

$newsandevents = array_merge($news, $events);
usort($newsandevents, "sort_news_and_events");
$newsandevents = array_splice($newsandevents, 0, 5);

$count_users = getNumberOfUsers();
$count_exps = getNumberOfExperiments();
$count_sessions = getNumberOfSessions();
$exps = getFeaturedExperimentsWithPhotos();
//$expsix = getFeaturedExperimentsWithPhotosBigThree();
//$vissix = getFeaturedVisualizationsWithPhotosBigThree();
//$actsix = getFeaturedActivitiesWithPhotosBigThree();

//$smarty->assign('expsix', $expsix);
//$smarty->assign('vissix', $vissix);
//svn$smarty->assign('actsix', $actsix);

$smarty->assign('events', $newsandevents);
$smarty->assign('six', $exps);
$smarty->assign('count_exps', $count_exps);
$smarty->assign('count_users', $count_users);
$smarty->assign('count_sessions', $count_sessions);
$smarty->assign('title', 'Featured Experiments');
$smarty->assign('user', $session->getUser());

if(strpos($_SERVER['HTTP_USER_AGENT'],'Android')!= false){
    $smarty->assign('content', $smarty->fetch('index.tpl'));
    $smarty->display('skeleton.tpl');
} else {
    $smarty->display('mobile/index.tpl');
}

?>
