<?php

require_once './includes/config.php';

global $db;
global $mdb;

//Make sure everything is posted
if( isset($_POST['t_head']) && isset($_POST['t_data']) && isset($_POST['t_eid']) && isset($_POST['t_sid']) ) {

    //Only update if theres a valid EID and SID
    if( is_numeric($_POST['t_sid']) && is_numeric($_POST['t_eid']) ) {
        $sid = (int)$_POST['t_sid'];
        $eid = (int)$_POST['t_eid'];
    
        $t_head = $_POST['t_head'];
        $t_data = $_POST['t_data'];
        
        //Strip spaces from fields array
        foreach($t_head as $index => $header) {
            $t_head[$index] = trim($header);
        } 
        
        //If theres a time field (we could organize by time but I'm not sure that thats right)
        foreach( $t_head as $index => $header ) {
            if( strtolower($header) == 'time' || strtolower($header) == 'date' ) {
                $indexOfTimeField = $index;
            }
        }

        $newData = array();
        
        //Create newData object with field names as array keys
        foreach( $t_data as $key => $data ) {

            $tmp = array();
        
            foreach( $t_head as $index => $header ){
                $tmp[$header] = $data[$index];
            }
            
            //Add experiment and session to the data
            $tmp['experiment'] = $eid;
            $tmp['session'] = $sid;
            
            $newData[] = $tmp;
        }
        
        //Drop the session before repopulating it
        $mdb->dropSession($eid, $sid);
        
        //Insert each row        
        foreach( $newData as $data ) {
            print_r($data);
            $worked[] = $mdb->insert('e' . $eid, $data);
        }
        
        $error = 0;
        
        foreach( $worked as $w ) {
            if($w != 1) {
                $error = 1;
            }
        }
        
        if($error) {
            echo 'Were sorry, something went wrong. Please try again!';
        } else {
            echo 'Successfully updated Experiment: ' . $eid;
        }
            
    }
}