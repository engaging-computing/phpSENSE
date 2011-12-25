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