<!DOCTYPE html>
<html>
<head>
	<title>Latest Firmwares Found</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{
			font-family: Consolas, Lucida Console, Courier;
			font-size: 12px;
			}
		td{
			padding-top: 3px;
			padding-bottom: 3px;
			}
		tr.row{
			background-color:rgba(255, 255, 224, 0.6);
			}
		tr.altrow{
			background-color:rgba(224, 224, 255, 0.6);
			}
	</style>
</head>
<body>


<table>
<caption>Most Current Firmware Found</caption>
<tr>
	<th>Year</th>
	<th>Make</th>
	<th>Model</th>
	<th>Firmware</th>
</tr>

<?php
include './vindecoder/vin_decoder.php';

$vehiclecount = 0;
$filecount = 0;

// loop through folder, glob() returns alphabetical listing
foreach (glob("./logs/*.txt") as $filename) {
	
	// get current VIN from file (first 17 characters of file name - NOT NECESSARILY A VIN)
	$vin = substr($filename, 7, 17);
	
	// if filename is shorter than 21 characters, the extension could be included in $vin.  Eliminate this.
	$vin = str_replace('.txt', '', $vin);  // TODO still an issue if filename is 17-20 characters
	
	// detect new vin from list (compare first 17 chars of file name)
	if ( $vin != $lastvin ) {
			$vehiclecount++;
	}
	$filecount ++;
	
	// initialize the variable that determines success
	$zsuccess = '';
	
	// open first file for vin, read data
	$myfile = fopen($filename, "r") or die("Unable to open file!");
	$i = 0;
	
	// check file size to see if we got a full parse, just header will be less than 1k
	if ( filesize($filename) > 1024 ){
		$goodparse = '&#10003;';
	}else{
		$goodparse = '&nbsp;';
	}
	$logdate = date('Y-m-d', filemtime($filename));
	
	while(!feof($myfile)) {
		$i++; // line number
		
		// read information from log header	
		if ( $i == 1 ) { // type of file
			$logtype = trim(substr(fgets($myfile), 8, 3));  // three random hex in beginning of file throws offset
		} else if ( $i == 3 ) { // board year
			$zbyear = trim(substr(fgets($myfile), 19, 4));
		} else if ( $i == 4 ) { // VIN
			$zvin = trim(substr(fgets($myfile), 19, 17));
		} else if ( $i == 5 ) { // firmware version
			$zfirm = trim(substr(fgets($myfile), 19, 3));
		} else if ( $i == 6 ) { // board revision
			$zboard = 'rev' . trim(substr(fgets($myfile), 19, 3));
		} else if ( $i == 7 ) { // model identifier
			$zmodel = trim(substr(fgets($myfile), 19, 3));
		} else if ( $i == 9 ) { // look for Printing entry on header
			$zsuccess = trim(substr(fgets($myfile), 0, 8));
		} else {
			if ( $i > 9 ) {
				break; // no more info needed, leave loop
			} else {
				// still read a line to advance to the next one
				$x = fgets($myfile);
			}
		}
	} //while
	fclose($myfile);
	
	// Check for successful header parse
	if ( $zsuccess <> 'Printing' or strlen($zvin) <> 17 ) {
		// bad header, have no data about bike other than year from VIN in filename
		$zvin = $vin;  // try to glean VIN from filename because not found in header
		// clear out old/bad log lookup data
		$zmodel = '--';
		$zbyear = '--';
		$zboard = '--';
		$zfirm = '--';
	}



	// record results
	if (is_numeric($zfirm)){
		
		// run vin decoder
		$dvin = decode_vin($zvin);
		if ( $dvin <> '' and $dvin['valid'] ) {   //check for bad decode
			//echo "{$dvin['year']}{$dvin['model']}{$zfirm}<br/>";
		
			// check if firmware greater than already recorded
			if ( $zfirm > $firmv[$dvin['year'] . $dvin['model']] or $firmv[$dvin['year'] . $dvin['model']] == '' ) {
				$firmv[$dvin['year'] . $dvin['model']] = $zfirm;
			}	
		}
	}

	
	// record current vin for comparison to next line (first 17 chars of file name)
	$lastvin = $vin;
} //foreach in directory


// Write out summary
arsort($firmv, 2);
foreach($firmv as $x => $x_value) {
    echo "<tr><td>" . substr($x, 0, 4) . "</td><td>Zero</td><td>" . substr($x, 4) . "</td><td>$x_value</td></tr>\r\n";
}


?>
</table>
<br />
<br />
<a href="./">Home</a>

</body>
</html>
