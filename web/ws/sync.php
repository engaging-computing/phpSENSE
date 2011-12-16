<?php

require_once '../includes/config.php';
error_reporting(E_ALL);
set_time_limit(0);

define('DEST_DIR', '/Users/admin/Sites/test/data/'); // Set where you'd like your data to go, FULL PATH!
define('SERVER_NAME', 'isense.cs.uml.edu');

// Get the experiments and their sessions
$sql = "SELECT * FROM experimentSessionMap";
$data = $db->query($sql);

$esMap = array();

//$esMap["4"] = array("34", "35");

// Create a map from experiment id to session id
foreach($data as $datum) {
    if(!array_key_exists($datum['experiment_id'], $esMap)) {
        $esMap[$datum['experiment_id']] = array();
    }
    
    $esMap[$datum['experiment_id']][] = $datum['session_id'];
}


echo "Finished getting the experiments and session id<br/>";
echo "Starting download...<br/>";

$archives = array();

// Run through the map, downloading the sessions for each experiment
foreach($esMap as $e => $s) {
   
    // Join the sessions to 1 arg
    $session_string = implode(",", $s);
    
    // Build URL to request experiment data, then get the zip
    $url = "http://isense/actions/package.php?eid={$e}&sessions=$session_string";

	$c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $url);
    $contents = curl_exec($c);
   	curl_close($c);

    $time = time();
    $zip_filename = "/tmp/archive_{$e}.zip";
    file_put_contents($zip_filename, $contents);
    $archives[] = $zip_filename;
        
}

echo "Done!<br/>";
echo "Processing Data Files...<br/>";

foreach($archives as $archive) {
    
    $eid = str_replace(array('/tmp/archive_', '.zip'), "", $archive);
    $extract_dir = "/tmp/{$eid}/";
    
	echo "Storing Experiment #{$eid}...";

	if(!file_exists($extract_dir)) {
        mkdir($extract_dir); // Makes dir if its not there
    }

	$zip = new ZipArchive();
	if(($x = $zip->open($archive)) === TRUE) {
		$zip->extractTo($extract_dir);
	}
	else {
		echo "Failed!<br/>";
		exit;
	}
    
    $dir = DEST_DIR . "{$eid}/";
    if(!file_exists($dir)) {
        mkdir($dir); // Makes dir if its not there
    }
    
    $files = scandir($extract_dir);

    foreach($files as $file) {
        if($file != "." && $file != "..") {
            $sid = str_replace(".txt", "", $file);
            
            $n_file = $dir . $file;
            rename($extract_dir . $file, $n_file);
            
            $sql = "UPDATE data SET uri = '{$n_file}' WHERE session_id = '{$sid}'";
            $db->query($sql);
        }
    }

	echo "Done<br/>";
}

echo "All Done!<br/>";


?>