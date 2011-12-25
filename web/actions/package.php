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

require_once '../includes/config.php';
error_reporting(0);

// Grab params we need, and create temp file name
$time = time();
$eid = $_REQUEST['eid'];
$sids = ( (strpos($_REQUEST['sessions'], ",") !== FALSE) ? explode(",", $_REQUEST['sessions']) : array($_REQUEST['sessions']) );


if(count($sids) == 1) {

    $data = getData($eid, $sids[0], true);
    
    
    
    $output_str = "";
    
    foreach($data as $datum) {
        $tmp = join(",", $datum);
        $output_str .= substr($tmp, 0, strlen($tmp)) . "\r\n";
    }
    
    $filename = $sids[0] . ".csv";
    header('Content-type: text/csv');
    header("Content-disposition: attachment; filename={$filename}");
    echo $output_str;
    
}
else {

    $dir = "/tmp";
    $filename = "/tmp/Experiment_{$eid}_{$time}.zip";
    $filelist = array();
    $tmpfilelist = array();

    foreach($sids as $sid) {
        $tmp_file = "/tmp/{$sid}.csv";
        $filelist[] = $tmp_file;
        $tmpfilelist[] = "{$sid}.csv";
        
        $tmp_handle = fopen($tmp_file, "w");
        
        $data = getData($eid, $sid, true);
        //print_r($data);
        foreach($data as $datum) {
            fputcsv($tmp_handle, $datum);
        }
    }

    $filelist_string = implode(" ", $tmpfilelist);
    $cmd = "cd {$dir}; zip -r -D {$filename} {$filelist_string} > /dev/null";
    system($cmd, $x);
    
    foreach($filelist as $f) {
        unlink($f);
    }

    $short_filename = str_replace("/tmp/", "", $filename);

    header('Content-type: application/octet-stream');
    header("Content-disposition: attachment; filename={$short_filename}");

    $f = file_get_contents($filename);
    echo $f;
}



?>
