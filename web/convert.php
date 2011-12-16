<?php

require_once 'includes/config.php';

if( isset($_POST['exp']) ) {
    if( $_POST['count'] > 0 && $_POST['confirm']){
        
        for($i = 0; $i < $_POST['count']; $i++) {
            $data[] = $_POST['dp'.$i];
            $id[] = $_POST['dpold'.$i];
        }
        
        $mdb->updateTime( 'e' . $_POST['exp'], $data, $id );
        
        echo 'Successfully update experiment #' . $_POST['exp'];
    }
} else {

    if(isset($_REQUEST['e']))
        $i = $e;
    else
        $i = 278;
        
     if( $i < 10 ) {
     	 $app = '00' . $i;
     } else if ( $i < 100 ) {
       	 $app = '0' . $i;
     } else {
         $app = $i;
     }

     $result = $mdb->find('e' . $app);

     echo '<head><body>';
     //print_r($result);

        echo '<form action="convert.php" method="post"><input type="hidden" name="exp" value="' . $app . '" /><input type="hidden" name="count" value="' . count($result) . '" /><table><tr><td>Old Value</td><td>New Value</td></tr>';

     	foreach($result as $index => $dp ) {
     	    
     	    if(isset($dp['time']) || isset($dp['Time'])) {
     	        if(isset($dp['time']))
     	            $key = 'time';
     	        else
     	            $key = 'Time';
     	            
     	        if(is_numeric($dp[$key])) {
     	            if($dp[$key] < 9999999999) {
     	                echo '<tr><td>' . $dp[$key] . '</td><td>' . $dp[$key] * 1000 . '</td></tr>';
                        echo '<input type="hidden" name="dp' . $index . '" value="' . $dp[$key] * 1000 . '" />';
                        echo '<input type="hidden" name="dpold' . $index . '" value="' . $dp[$key] . '" />';
                        
     	            } else {
     	                echo '<tr><td>' . $dp[$key] . '</td><td>' . $dp[$key] . '</td></tr>';
                        echo '<input type="hidden" name="dp' . $index . '" value="' . $dp[$key] . '" />';
                        echo '<input type="hidden" name="dpold' . $index . '" value="' . $dp[$key] . '" />';
                        
                    }
     	        } else {
     	            echo '<tr><td>' . $dp[$key] . '</td><td>' . strtotime($dp[$key]) * 1000 . '</td></tr>';
                    echo '<input type="hidden" name="dp' . $index . '" value="' . strtotime($dp[$key]) * 1000 . '" />';
                    echo '<input type="hidden" name="dpold' . $index . '" value="' . $dp[$key] . '" />';
                    
     	        }
     	            
     	    }
	}

	echo '</table><input type="checkbox" name="confirm"/><input type="submit" value="Submit" /></form>';
}