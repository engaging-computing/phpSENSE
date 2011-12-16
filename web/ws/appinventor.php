<?php

require_once '../includes/config.php';

define('TAG', 0);
define('VALUE', 1);

if( strpos($_SERVER['REQUEST_URI'], '?') ) {
    
    $output = '[[], [], [';
    
    $uri = $_SERVER['REQUEST_URI'];
    
    $uri = substr( $uri, strpos($uri, '?') + 1, strlen($uri) );
        
    $uri = explode('&', $uri);
    foreach( $uri as $key => $ui )
        $uri[$key] = explode('=' ,$ui);
    
    foreach( $uri as $ui ) {
        if( $ui[TAG] == 'experiment' ) {
            $experiment = safeString($ui[VALUE]);
        }
        if( $ui[TAG] == 'sessions' ) {
            $sessions = safeString($ui[VALUE]);
        }
    }
    
    $data = getData($experiment, $sessions);
    
    //$output .= $data;
    foreach( $data as $key => $dat ) {
        if( $key != 0 )
            $output .= ", [";
        else
            $output .= "[";

        foreach( $dat as $index => $da) {
            if( $index )
                $output .= ", ";
            if( gettype($da) == 'string' )
                $output .=  '"' . $da . '"';
            else
                $output .= $da;
            
        }
        
            $output .=  "]";

    }
        
    $output .= ']];';
    
    //print_r($data);
    
    //print_r($uri);
    
    echo $output;
    
}
//$_POST['action'] = 'postData';

//$_POST['data'] = '(( 296, Working App Example, PLEASE WORK!, Ashby MA, 833 Fitchburg State rd. ),( ( 12345, 22 ), ( 12346, 12 ) )';

if( isset( $_POST['action'] )) {
    if( $_POST['action'] == 'login' ) {
        if( isset($_POST['username']) && isset($_POST['password']) ){
            $user = safeString($_POST['username']);
            $pass = safeString($_POST['password']);
            
            echo $session->login($user, $pass);

        }
    } else if ( $_POST['action'] == 'postData' ) {
        
        $tmp = $_POST['data'];
        
        $open = strpos($tmp, '(');
        $close = strrpos($tmp, ')');
        
        if( isset($open) && isset($close) ) {
            $tmp = substr($tmp, $open+1, -1);
            
            $open = strpos($tmp, '(');
            $close = strpos($tmp, ')');
            $meta = substr($tmp, $open+1, $close);
            $meta = explode(',', $meta);
            $tmp = substr($tmp, $close);
            $open = strpos($tmp, '(');
            $close = strrpos($tmp, ')');
            $tmp = substr($tmp, $open+1, -1);
                        
            $work = 1;

			//This is new code that does the stuff with the things. -Skittles

            while( $work ) {
                $open = strpos($tmp, '(');
                $close = strpos($tmp, ')');
                if( $open >= $close ) {
                    echo 'Error Parsing: Mismatched Parenthesis';
                    break;
                }
                
                $dat = substr($tmp, $open+1, ($close-$open)-1);
                $tmp = substr($tmp, $close+1);
                
                $data[count($data)] = explode(',', $dat);
                $work = strpos($tmp, '(');
       	}}
	
	foreach($data as $dat)
	  foreach($dat as $d)
	    if( strpos($d, '.') ) {
	      $d = floatval($d);
	    } else {
	      $d = intval($d);
	    } 
                                    
            unset($open); unset($close); unset($tmp);
            
	    $meta[0] = intval($meta[0]);

        $sid = createSession(	$session->generateSessionToken(), 
				$meta[0], 
				safeString($meta[1]), 
				safeString($meta[2]), 
				safeString($meta[3]), 
				safeString($meta[4]), 
				"United States", // Default country
				1, // Default permission bits
				1, // Default permission bits
				1, // Default permission bits
				'');

		putData($meta[0], $sid, $data);
        } else {
            echo 'Invalid Format: Please Try Again!';
        }

    }
//}

?>




