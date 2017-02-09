<!DOCTYPE html>
<html>
<head>
	<title>Logs</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<style type="text/css">
		body{
			font-family: Consolas, Lucida Console, Courier;
			font-size: 12px;
			}
		td{
			padding-top: 3px;
			padding-bottom: 3px;
			}
		.vin{background-color: #FFeeee;}
		.log{background-color: #eeeeFF;}
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
<caption>Summary of log file data</caption>
<tr>
	<th class="vin">Serial</th>
	<th class="vin">Year</th>
	<th class="vin">Make</th>
	<th class="log">Log</th>
	<th class="log">Model</th>
	<th class="log">Board</th>
	<th class="log">Firmware</th>
	<th class="vin">Type</th>
	<th class="vin">Line</th>
	<th class="vin">Model</th>
	<th class="vin">HP</th>
	<th class="vin">Color</th>
	<th class="log" title="Full parse successful with log entries?">&#10003;?</th>
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
	
	// run vin decoder
	$dvin = decode_vin($zvin);
	
	if ( $dvin == '' ) {
		// initialize array values for bad decode
		$dvin['year'] = '';
		$dvin['make'] = '';
		$dvin['type'] = '';
		$dvin['line'] = '';
		$dvin['model'] = '';
		$dvin['hp'] = '';
		$dvin['color'] = '';
		$dvin['valid'] = false;
	}
	
	if ( $zsuccess == 'Printing' || $dvin['valid'] ) {
		// write out table row
		if ( $vehiclecount % 2 == 0 ) {
			echo '<tr class="altrow">';
		}else{
			echo '<tr class="row">';
		}
		echo "<td>" . substr($zvin, 12, 5) . "</td>";
		echo "<td>{$dvin['year']}</td>";
		echo "<td>{$dvin['make']}</td>";
		echo "<td>$logtype</td>";
		echo "<td>$zmodel</td>";
		echo "<td>$zbyear $zboard</td>";
		echo "<td>$zfirm</td>";
		echo "<td>{$dvin['type']}</td>";
		echo "<td>{$dvin['line']}</td>";
		echo "<td>{$dvin['model']}</td>";
		echo "<td>{$dvin['hp']}</td>";
		echo "<td>{$dvin['color']}</td>";
		echo "<td>$goodparse</td>";
		echo "</tr>\r\n";
	}
	
	// record current vin for comparison to next line (first 17 chars of file name)
	$lastvin = $vin;
	
} //foreach in directory
?>
</table>
<b><?=$vehiclecount?> vehicles, <?=$filecount?> log files.</b><br />
<br />
<table>
	<caption>Data Sources</caption>
	<tr><th class="vin">Data from VIN decoder</th></tr>
	<tr><th class="log">Data from log file parser</th></tr>
</table>
<br />
<br />
<a href="./">Home</a>

</body>
</html>
