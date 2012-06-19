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
    
    public $relVis = array('Table');
    
    public $fields = array();
    public $sessions = array();
   

    // DO NOT USE UNIT_IDs FOR TYPE CHECKS
    public function getTimeField() {
        foreach( $this->fields as $index=>$field )
            if( $field->type_id == 7 )
                return $index;
    }

    /* Turn on the relevant vizes */
    public function setRelVis() {
        
        /* See how much data the experiment has */
        $total = 0;
        foreach( $this->sessions as $session ) {
            $total += count($session->data);
        }
      
        /* If there is more than one data point in a session add the following vizes */
        if( $total > 1 ) {
            $this->relVis = array_merge(array('Scatter', 'Bar', 'Histogram'), $this->relVis); 

            /* if a time field exists, add timeline */
            foreach( $this->fields as $field ){
                if ($field->type_id == 7) {
                    $this->relVis = array_merge(array('Timeline'), $this->relVis); 
                }
            }
        }

        /* Add the map last because it should always be first. */
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

    public function removeTextFields() {
        
        function FieldFilter($field) {
            return $field->typeID != 37;
        }
        
        $data->fields = array_filter($data->fields, FieldFilter);
    }

    public function sortTime() {
        
        $cmpIndex = -1;
    
        //First try Time
        foreach ($this->fields as $index=>$field) {
            if ($field->typeID == 7) {
                $cmpIndex = $index;
                break;
            }
        }
    
        //Else try first numeric
        if ($cmpIndex == -1 && count($this->fields) > 0) {
            $cmpIndex = 0;
        }
        
        //Else use first textual
    
        $DataPointCmp = function($a, $b) use ($cmpIndex) {
        
            if ($cmpIndex == -1) {
                //Textual Compare
                
                return strcmp($a->Text[0], $b->Text[0]);
            }
            else {
                //Numeric Compare
                
                return $a->numeric[$cmpIndex] < $b->numeric[$cmpIndex] ? -1 : 1;
            }
        };
        
        //Sort
        foreach ($this->sessions as $index=>$session) {
            usort($this->sessions[$index]->dataPoints, $DataPointCmp);
        }
        
    }
    
    public function addDataPointNumbers() {
        
        array_unshift($this->fields, new DataField(-1, "Datapoint #", 21, 66, "Numeric", "Number", "#", true));
        
        foreach($this->sessions as &$sess) {
            
            $dpiter = 1;
            
            foreach($sess->dataPoints as &$dp) {
                
                array_unshift($dp->numeric, $dpiter);
                
                $dpiter += 1;
                
            }
            
        }
        
    }
    
};

class DataSession {
    
    public $sessionID;
    public $isVisible = true;
    
    public $metaData = array();
    public $dataPoints = array();
    public $pictures = array();
    
};

class DataField {
    
    public $fieldID = 0;
    public $fieldName;
    public $typeID = 0;
    public $unitID = 0;
    public $typeName = 0;
    public $unitName = 0;
    public $unitAbbreviation;
    public $isVisible = false;
    
    public function __construct() {
        $this->fieldID = func_get_arg(0);
        $this->fieldName = func_get_arg(1);
        $this->typeID = func_get_arg(2);
        $this->unitID = func_get_arg(3);
        $this->typeName = func_get_arg(4);
        $this->unitName = func_get_arg(5);
        $this->unitAbbreviation = func_get_arg(6);
        $this->isVisible = func_get_arg(7);
        
        //set default of geolocation and text to not visible
        /*if ($this->type_id == 37 || $this->type_id == 19) {
            $this->visibility = 0;
        }*/
    }
    
};


/*
 * The raw fields should be assigned using SetFields
 * before the constructor is called. This will allow
 * proper sorting of numeric and textual fields.
 */
class DataPoint {
    
    public $text = array();
    public $numeric = array();
    
    static public $fields;
    
    static public function SetFields($fields) {
        self::$fields = $fields;
    }
    
    public function __construct($dataArray) {
        
        foreach (self::$fields as $index=>$field) {
            //Check for String
            if ($field->typeID == 37) {
                $this->text[] = $field->fieldName . ": " . $dataArray[$index];
            }
            else {
                if (!is_numeric($dataArray[$index])) {
                    $this->numeric[] = "";
                }
                else {
                    $this->numeric[] = $dataArray[$index];
                }
            }
        }
    }
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
    $visible = true;
    
    //Make only the first non-geolocation numeric field visible
    //All time fields visible, and no text fields visible        
    foreach( $fields as $index=>$field ) { 
        if ($pick && $field['type_id'] != 37 && $field['type_id'] != 19 && $field['type_id'] != 7) {
            $visible = true;
            $pick = false;
        }
        else if ($field['type_id'] == 7) {
            $visible = true;
        }
        else {
            $visible = false;
        }
        
        $data->fields[$index] = new DataField($field['field_id'], $field['field_name'], $field['type_id'], $field['unit_id'], $field['type_name'], $field['unit_name'], $field['unit_abbreviation'], $visible);
    }
    
    $newdata = array();

    //setup for data massaging
    DataPoint::SetFields($data->fields);
    function InitDataPoint($data) {
        return new DataPoint($data);
    }

    //Load sessions into Data object
    foreach( $sessions as $index=>$ses ) {        
        $data->sessions[$index] = new DataSession;
        $data->sessions[$index]->sessionID = $ses;
        $data->sessions[$index]->metaData = getSession($ses);
        $data->sessions[$index]->dataPoints = array_map(InitDataPoint, getData($data->eid, $ses)); //getData($data->eid, $ses);
        $data->sessions[$index]->pictures = getSessionPictures($ses);
    }

    /*print_r("POST: ");
    print_r($data->fields);
    reset($data->fields);*/

    //Filter out textual fields now that data is loaded.
    $data->removeTextFields();
    
    // NOTE: I'm not sure if this is nessiary anymore.
    //Parse time data as ms since epoch time
    //$data->setTime();
    
    //Sorts each session by time if time is a field
    $data->sortTime();
    
    //Adds data point number field to data object
    $data->addDataPointNumbers();
    
    //Determine witch vises are relevant
    $data->setRelVis();

    echo 'var data =' . json_encode($data) .';';    
}
?>
