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

    public $experimentID;
    public $experimentName;
    public $allVis       = array('Map','Timeline','Scatter','Bar','Histogram','Table','Motion','Photos');
    
    public $relVis       = array();
    
    public $fields       = array();
    public $dataPoints   = array();
    public $metaData     = array();
    public $normalFields = array();
    public $timeFields   = array();
    public $geoFields = false;
    public $hasPics = false;
  
    /* Turn on the relevant vizes */
    public function setRelVis() {
        
        /* See how much data the experiment has */
        $total = count($this->dataPoints);
        
        if ($total > 1) {       
            
            if((count($this->normalFields))>1){
                $this->relVis = array_merge(array('Scatter'), $this->relVis);     
            }
            if((count($this->timeFields))>0 && (count($this->normalFields))>0){
                $this->relVis = array_merge(array('Timeline'), $this->relVis);           
            }
            if((count($this->normalFields))>0){
                $this->relVis = array_merge(array('Bar','Histogram'), $this->relVis);     
            }
            
        }
        
        $this->relVis = array_merge(array('Table'), $this->relVis);
        
        if($this->geoFields){
            $this->relVis = array_merge(array('Map'), $this->relVis);
        }
        
        if ($total > 1 && (count($this->timeFields))>0 && (count($this->normalFields))>0){
            
            $this->relVis = array_merge(array('Motion'), $this->relVis);
            
        }
        
        $this->relVis = array_reverse($this->relVis);
        
    }  
    
};

class DataField {
    
    public $fieldID = 0;
    public $fieldName;
    public $typeID = 0;
    public $unitID = 0;
    public $typeName = 0;
    public $unitName = 0;
    public $unitAbbreviation;
    
    public function __construct() {
        $this->fieldID = func_get_arg(0);
        $this->fieldName = func_get_arg(1);
        $this->typeID = func_get_arg(2);
        $this->unitID = func_get_arg(3);
        $this->typeName = func_get_arg(4);
        $this->unitName = func_get_arg(5);
        $this->unitAbbreviation = func_get_arg(6);
    }
    
};

if(isset($_REQUEST['vid'])) {

     $vis = getSavedVis($_REQUEST['vid']);
     
     echo "var data = {savedData: '{$vis[0]['data']}', savedGlobals: '{$vis[0]['globals']}'};";

}

if(isset($_REQUEST['sessions'])) {

    //Create Data object
    $data = new Data;

    //Load session data
    $sessions = explode(" ", $_REQUEST['sessions']);
    
    $data->experimentID     = getSessionExperimentId($sessions[0]);
    $data->experimentName   = getNameFromEid($data->experimentID);

    //Load fields into Data object
    $fields = getFields($data->experimentID);
    
    //Make only the first non-geolocation numeric field visible
    //All time fields visible, and no text fields visible        
    foreach( $fields as $index=>$field ) { 
        
        $data->fields[$index] = new DataField($field['field_id'], $field['field_name'], $field['type_id'], $field['unit_id'], $field['type_name'], $field['unit_name'], $field['unit_abbreviation']);

    }
    
    array_unshift($data->fields, new DataField(-1, "Session Name(ID)", 37, 81, "Text", "Text", ""));
    
    $newdata = array();

    $sessionNames = getSessionTitles($sessions);
    
    //Load sessions into Data object
    foreach( $sessions as $index=>$ses ) {

        //Make Session ID-Name value
        $idName = "" . $sessionNames[$ses] . "(" . $ses . ")";
        
        //Add Session ID-Name field to data
        $tmpData = getData($data->experimentID, $ses);
        foreach ($tmpData as $j=>$dataPoint) {
            array_unshift($tmpData[$j], $idName);
        }
        
        //Validate Numerics
        foreach ($tmpData as $j=>$dataPoint) {
            foreach ($data->fields as $k=>$field) {
                if ($field->typeID != 37)
                {
                    if(!is_numeric($dataPoint[$k])){
                    
                        $tmpData[$j][$k] = null;
                    }
                } else {
                    $tmpData[$j][$k] = $dataPoint[$k];
                }
            }
        }
        
        //add the data
        $data->dataPoints = array_merge($data->dataPoints, $tmpData);
        
        //Figure out the field types for the experiment.
        foreach($fields as $key=>$field){
            $t = $field['type_id'];
            
            //add timeFields
            if ($t == 7) {
                $data->timeFields = array_merge($data->timeFields,array($key));               
            }
            
            //add normalFields
            if ($t!=7 && $t!=19 && $t!=37){
                $data->normalFields = array_merge($data->timeFields,array($key));
            }
            
            if($t == 19){
                $data->geoFields = true;
            }
        }
        
        //Get session related meta data
        $data->metaData[$idName] = getSession($ses);
        $data->metaData[$idName]['pictures'] = getSessionPictures($ses);

        if(count($data->metaData[$idName]['pictures'])>0){
            $data->hasPics = true;
        }
    }
    
    //Determine witch vises are relevant
    $data->setRelVis();

    echo 'var data =' . json_encode($data) .';';    
}
?>
