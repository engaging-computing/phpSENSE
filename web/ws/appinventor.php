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




