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
// Returns the data requested in $_GET as a javascript object

Header('content-type: application/x-javascript');

require_once '../includes/config.php';

class Data {

    public $eid;
    
    public $relVis = array('Scatter', 'Histogram', 'Bar', 'Table');
    
    public $fields = array();
    public $sessions = array();
   

	// DO NOT USE UNIT_IDs FOR TYPE CHECKS
    
    public function getTimeField() {
        foreach( $this->fields as $index=>$field )
            if( $field->type_id == 7 )
                return $index;
    }

    public function setRelVis() {
        foreach( $this->fields as $field )
            if( $field->type_id == 7 )
                $this->relVis = array_merge(array('Timeline'), $this->relVis);
        $this->relVis = array_merge(array('Map'), $this->relVis);    
    }  
    
    public function setTime() {
        
        $time = $this->getTimeField();
        
        if( isset($time) )
            for( $j = 0; $j < count($this->sessions); $j++ )
                for( $n = 0; $n < count($this->sessions[$j]->data); $n++ )
                    if( !is_numeric($this->sessions[$j]->data[$n][$time]) )
                        $this->sessions[$j]->data[$n][$time] = strtotime(stripslashes($this->sessions[$j]->data[$n][$time]));
                
    }
    
    public function sortTime() {
        $time = $this->getTimeField();

        for( $ses = 0; $ses < count($this->sessions); $ses++ ) {
            
            $cur = 1;
            $stack[1]['l'] = 0;
            $stack[1]['r'] = count($this->sessions[$ses]->data) - 1;
            
            
            do{
                $l = $stack[$cur]['l'];
                $r = $stack[$cur]['r'];
                $cur--;
                                
                do{
                    $i = $l;
                    $j = $r;
                    $tmp = $this->sessions[$ses]->data[(int) ($l+$r)/2][$time];
                    
                    do {
                        while( $this->sessions[$ses]->data[$i][$time] < $tmp )
                            $i++;
                        
                        while( $tmp < $this->sessions[$ses]->data[$j][$time])
                            $j--;
                        
                        if( $i <= $j ) {
                            $w = $this->sessions[$ses]->data[$i];
                            $this->sessions[$ses]->data[$i] = $this->sessions[$ses]->data[$j];
                            $this->sessions[$ses]->data[$j] = $w;
                            
                            $i++;
                            $j--;
                        }
                        
                    } while( $i <= $j );
                    
                    if( $i < $r ) {
                        $cur++;
                        $stack[$cur]['l'] = $i;
                        $stack[$cur]['r'] = $r;
                    }
                    
                    $r = $j;
                    
                } while( $l < $r );
            } while( $cur != 0 );
        }
    }
    
};

class Ses {
    
    public $sid;
    public $visibility = 1;
    
    public $meta = array();
    public $data = array();
    public $pictures = array();    

    public function is_visible() { return $this->visibility; }
    public function setVisibility($v) { $this->visibility = $v; }
    
};

class Field {
    
    public $field_id = 0;
    public $name;
    public $visibility = 0;
    public $type_id = 0;
    public $unit_id = 0;
    public $type_name = 0;
    public $unit_name = 0;
    public $unit_abb;
    
    public function __construct() {
        $this->field_id = func_get_arg(0);
        $this->name = func_get_arg(1);
        $this->type_id = func_get_arg(2);
        $this->unit_id = func_get_arg(3);
        $this->type_name = func_get_arg(4);
        $this->unit_name = func_get_arg(5);
        $this->unit_abb = func_get_arg(6);
        $this->visibility = func_get_arg(7);
        
        //set default of geolocation and text to not visible
        /*if ($this->type_id == 37 || $this->type_id == 19) {
            $this->visibility = 0;
        }*/
    }
    
    public function is_visible() { return $this->visibility; }
    public function setVisibility($v) { $this->visibility = $v; }
    
    
};


if(isset($_REQUEST['sessions'])) {

    //Create Data object
    $data = new Data;

    //Load session data
    $sessions = explode(" ", $_REQUEST['sessions']);
    
    $data->eid = getSessionExperimentId($sessions[0]);

    //Load fields into Data object
    $fields = getFields($data->eid);
    
    //print_r($fields);
    $pick = true;
    $visible = 1;
            
    foreach( $fields as $index=>$field ) { 
        if ($pick && $field['type_id'] != 37 && $field['type_id'] != 19 && $field['type_id'] != 7) {
            $visible = 1;
            $pick = false;
        }
        else if ($field['type_id'] == 7) {
            $visible = 1;
        }
        else {
            $visible = 0;
        }
        
        $data->fields[$index] = new Field($field['field_id'], $field['field_name'], $field['type_id'], $field['unit_id'], $field['type_name'], $field['unit_name'], $field['unit_abbreviation'], $visible);
    }
    
    //Determine witch vises are relevant
    $data->setRelVis();
        
    //Load sessions into Data object
    foreach( $sessions as $index=>$ses ) {        
        $data->sessions[$index] = new Ses;
        $data->sessions[$index]->sid = $ses;
        $data->sessions[$index]->meta = getSession($ses);
        $data->sessions[$index]->data = getData($data->eid, $ses);
        $data->sessions[$index]->pictures = getSessionPictures($ses);
    }
    
    
    //Parse time data as ms since epoch time
    $data->setTime();
    
    //Sorts each session by time if time is a field
    $data->sortTime();
        
    
    //print_r($data);
   /* 
    //foreach( $data->sessions as $ses ) {
        $dcount = count($data->sessions[0]->data[0]);
     
        if( $fcount != $dcount )
            $data->fields = array_slice($data->fields, 1);
        else {
            foreach($data->fields as $key=>$field) {
                if( $field->type_id == 7 && $field->unit_id != 1234)
                    $old_time = $key;
            }
                        
            foreach( $data->sessions as $skey=>$ses ) {
                foreach( $ses->data as $dkey=>$dP) {
                        $data->sessions[$skey]->data[$dkey] = array_merge(array_slice($dP, 0, $old_time), array_slice($dP, $old_time+1)); 
                }
            }
    
            if( isset($old_time) && $old_time == 0) {
                $data->fields = array_slice($data->fields, 1);
            } else if( $old_time == count($data->fields)-1 ) {
                $data->fields = array_slice($data->fields, 0, count($data->fields)-1);
            } else {
                $data->fields = array_merge(array_slice($data->fields, 0, $old_time), array_slice($data->fields, $old_time+1));
            }
            
	    }*/             
    //}
        
    //echo 'data["session"][0].is_visible = function() {alert("hi");};';
    //echo 'console.log(data["session"][0].is_visible());';

    echo 'var data =' . json_encode($data) .';';    
}
?>
