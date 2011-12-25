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