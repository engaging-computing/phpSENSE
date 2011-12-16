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
