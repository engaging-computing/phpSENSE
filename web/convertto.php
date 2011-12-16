<?php

require_once 'includes/config.php';

if( isset($_GET['go']) ) {
    if( $_POST['count'] > 0 && $_POST['confirm']){
        
        for($i = 0; $i < $_POST['count']; $i++) {
            $data[] = $_POST['dp'.$i];
            $id[] = $_POST['dpold'.$i];
        }
        
        $mdb->updateTime( 'e' . $_POST['exp'], $data, $id );
        
        echo 'Successfully update experiment #' . $_POST['exp'];
    }
} else {
    echo '<html><body>';
for( $app = 14; $app < 341; $app++ ) {
    /*if(isset($_REQUEST['e']))
        $i = $_REQUEST['e'];
    else
        $i = 278;
        
        $app = $i;
      */  
     $result = $mdb->find('e' . $app);
     $tmp = microtime();
     $tmp = explode(' ', $tmp);
          
     foreach($result as $index => $dp ) { 
       
  	    if(isset($dp['time']) || isset($dp['Time'])) {
  	        if(isset($dp['time']))
  	            $key = 'time';
  	        else
  	            $key = 'Time';
  	            

 	        if(is_numeric($dp[$key])) {
 	             if($dp[$key] < 999999) {
 	                $result[$index][$key] = intval($tmp[1] * 1000 + ($dp[$key] * 1000)) ;
     	        } else if($dp[$key] < 9999999999999) {
                    $result[$index][$key] = $dp[$key] * 1000 ;
                } else {
                    $result[$index][$key] = $dp[$key];
                }
            } else {
                if($dp[$key] == ' ' || $dp[$key] == null) {
                    $result[$index][$key] = 'blank';
                } else if(strtotime($dp[$key])) {
                    $result[$index][$key] = strtotime($dp[$key]) * 1000;
                } else {
                    if( strpos( $dp[$key], ' ' ) )
                        $ishms = explode( " ", $dp[$key] );
                    
                    if ( isset($ishms) && count($ishms) == 2 ) {
                        $hms = explode( ":", $dp[$key] );
                        if( count($hms) == 4 ) {
                            $nt = strtotime( $hms[0] . $hms[1] . $hms[2] ) * 1000 + intval($hms[3]);
                            $result[$index][$key] = $nt;
                        }
                    } else {
                        $result[$index][$key] = 'remove';
                    }
                }
            }
        }	                                  
	}
		
	if(isset($_GET['yes']) && isset($result))
        $r = $mdb->updateTime( 'e' . $app, $result, $id );

    unset($result);

    echo $app . '<br />';
}
echo '</body></html>';
}