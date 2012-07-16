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

$tmpusr = $session->getUser();

global $mdb;

$top = <<<EndOfHTML
    <head>
	    <script type="text/javascript" src="../html/js/lib/jquery.js"></script>
	    <script type="text/javascript" src="../html/js/lib/jquery-ui.js"></script>
        <script>
        $(document).ready(function() {
            $('#dry_run').click(function (){
                eids = $('#exp_ids').val();
                eid = eids.split(' ');
                redirrect = window.location.href + '?eids=';

                for( tmp in eid ) {
                    if(tmp != eid.length) {
                        if(eid[tmp] != ' ' && eid[tmp] != ''){
                            redirrect += eid[tmp] + '+';
                        }
                    } else { 
                        redirrect += eid[tmp];
                    }
                }
                
                window.location.href = redirrect;
                
            });
            
            $('#run_all').click(function(){
                window.location.href = window.location.href + '?eids=all&verify=yes';
            });
        });    
            
            
        </script>
    </head>
EndOfHTML;

echo $top;

if ($tmpusr['administrator']) {
    if (isset($_REQUEST['eids'])) {        
        
        if (isset($_REQUEST['verify']) && $_REQUEST['verify'] == 'yes') {
            
            $eids = explode(" ", $_REQUEST['eids']);

            if ($eids[0] == "all") {
                $eids = array();
                echo "<pre>";
                foreach ($mdb->db->listCollections() as $index => $coll) {
                    $name = $coll->getName();

                    if ($name[0] == 'e') {
                        $eids[] = trim($name, 'e');
                    }
                }
                echo "</pre>";
            }

            echo "Starting on... <pre>" . print_r($eids, true) . "</pre><br>";
            
            foreach ($eids as $index => $eid) {
                $fields = getFields($eid);
                
                echo 'Trying EID:' . $eid . '<br>';
                
                foreach ($fields as $fieldIndex => $field) {
                    if ($field['type_id'] == 7) {
                        //Do Time Fix
                        
                        $func = "function fix() {
                            var ret = [];
                            var data = db.e" . $eid . ".find({}).forEach(function(obj) {
                                var fixed = new Date(obj[\"" . $field['field_name'] . "\"] + ' GMT');
                                var replacement;
                        
                                if (!isNaN(fixed.valueOf())) {
                                    replacement = NumberLong(fixed.valueOf());
                                }
                                else if (!isNaN(Number(obj[\"" . $field['field_name'] . "\"]))){
                                    replacement = NumberLong(Number(obj[\"" . $field['field_name'] . "\"]));
                                }
                                else {
                                    replacement = 'NaN';
                                }
                        
                        
                                ret.push('Replacing ' + obj[\"" . $field['field_name'] . "\"] + ' with ' + replacement + '\\r\\n');
                                
                                db.e" . $eid . ".update({_id : obj['_id']}, {\$set : {" . $field['field_name'] . " : replacement}});
                                                                
                                });
                                 return ret;}";
                        echo "<pre>";
                        print_r($mdb->db->execute($func));
                        echo "</pre>";
                    }
                }
            }
        } else {
            $eids = explode(" ", $_REQUEST['eids']);

            if ($eids[0] == "all") {
                $eids = array();
                echo "<pre>";
                foreach ($mdb->db->listCollections() as $index => $coll) {
                    $name = $coll->getName();

                    if ($name[0] == 'e') {
                        $eids[] = trim($name, 'e');
                    }
                }
                echo "</pre>";
            }

            echo "<a href='timeFix.php?eids=";
            foreach($eids as $index => $e) {
                if( $index != sizeof($eids)-1)
                    echo $e . "+";
                else
                    echo $e;
            }
            echo "&verify=yes'>Click here to Verify!</a><br>";
            
            foreach ($eids as $index => $eid) {
                $fields = getFields($eid);
                
                //echo 'Trying EID:' . $eid . '<br>';
                
                foreach ($fields as $fieldIndex => $field) {
                    if ($field['type_id'] == 7) {
                        //Do Time Fix
                        
                        $func = "function fix() {
                            var ret = [];
                            var data = db.e" . $eid . ".find({}).forEach(function(obj) {
                                var fixed = new Date(obj[\"" . $field['field_name'] . "\"] + ' GMT');
                                var replacement;
                        
                                if (!isNaN(fixed.valueOf())) {
                                    replacement = NumberLong(fixed.valueOf());
                                }
                                else if (!isNaN(Number(obj[\"" . $field['field_name'] . "\"]))){
                                    replacement = NumberLong(Number(obj[\"" . $field['field_name'] . "\"]));
                                }
                                else {
                                    replacement = 'NaN';
                                }
                        
                        
                                ret.push('Replacing ' + obj[\"" . $field['field_name'] . "\"] + ' with ' + replacement + '\\r\\n');                                                                
                                });
                                 return ret;}";
                                 
                        $returnVal = $mdb->db->execute($func);
                        
                        echo "<pre><table>";
                        foreach($returnVal['retval'] as $val) {
                            echo "<tr><td>" . $val . "</td></tr>";
                        }
                        echo "</table></pre>";
                    }
                }
            }
        }
    } else {
        echo '<h1>Time Fix</h1><br/><h2>Enter the sessions you want to update:</h2><br/><form><input id="exp_ids" type="text" style="width:100%" /><br/>';
        echo '<input id="dry_run" type="button" value="Dry run"><br/><br/><h2>Update all:</h2><br/><input id="run_all" type="button" value="Run All"></form>';
    }
} else {
    echo 'Silly non-Admin what are you doing here?';
}
?>