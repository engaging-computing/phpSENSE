<?php
    require_once 'includes/config.php';

    $sessions   = getSessionsForExperiment($_GET['id']);
    $exp = getExperiment($_GET['id']);
  
    $smarty->assign('sessions', $sessions);
    $smarty->assign('exp',$exp['name']);
            
    $smarty->display('mobileexperiment.tpl');
?>