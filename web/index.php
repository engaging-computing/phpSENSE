<?php

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
$smarty->assign('content', $smarty->fetch('index.tpl'));

$smarty->display('skeleton.tpl');

?>