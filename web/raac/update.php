<?php

require_once '../includes/config.php';

if(isset($_POST['data'])) {
    
    $data = $_POST['data'][0];
    $keys = $_POST['data'][1];
        
    foreach( $keys as $i => $key ) {
        
        if( $key == 'experiment' || $key == 'Experiment' )
            $exp = $data[0][$i];
            
        if( $key == 'session' || $key == 'Session' )
            $ses = $data[0][$i];
        
    }
    
    if( isset($exp) && isset($ses) ) {
        
		

        $mdb->dropUpdate($data, $keys, $exp, $ses);
        if( isset($data) )
			echo 'true!';
        
    }
    
} else {
    echo 'Tacos';
}

?>