<?php
    require_once 'includes/config.php';
    
        $sessions   = getSessionsForExperiment(350);
        $exp = getExperiment(350);
        $smarty->assign('sessions', $sessions);
        $smarty->assign('exp',$exp['name']);
        
    
    $smarty->display('mobileexperiment.tpl');
?>