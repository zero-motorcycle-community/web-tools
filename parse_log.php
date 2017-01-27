<?php
/*************************************
     WRAPPER FOR PYTHON SCRIPT
*************************************/

// grab filename from querystring
$log_file = $_GET['logfile'];

// validate querystring input - start in log directory and ends in .bin extension
if ( substr($log_file, -4, -4 ) != '.bin' && substr($log_file, 0, 7) != './logs/' ) {
	echo "Incorrect input file.";
	die();
}

// define output file (same name, change extension to .txt as parser does)
$parse_file = substr($log_file, 0, -4) . '.txt';


// run parser *REQUIRES PYTHON 2.7* - will overwrite any existing file
$result = exec('/usr/bin/python ./python/zero_log_parser.py ' . $log_file);


/* SAVING BIN FILES FOR NOW, UNCOMMENT TO DELETE AFTER PROCESSING
// delete original bin file
if (file_exists($log_file)) {
	unlink($log_file);
}
*/


// check for success - python script will output "Saved to ...." if successful
if (strpos($result, 'Saved to') !== false){
	// success, redirect to parsed text log file
	header("Location: $parse_file");
	die();
} else {
	
	// failed.  display message accordingly and result from python script.
	echo "Parsing of $log_file failed.<br />\r\n<br />\r\n [$result]<br />\r\n<br />\r\n";
	
	// sometimes script still creates a portion of a readable file.  attempt to view it here.
	echo 'Try downloading the results anyway: <a href="' . $parse_file . '">' . $parse_file . "</a><br />\r\n";
	
	die();
}

?>
