<!DOCTYPE html>
<html>
<head>
	<title>Zero Motorcycles Online VIN Decoder</title>
	<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>

<h1>Zero Motorcycles VIN Decoder</h1>

<!-- DEMO VIN: 538SM5Z21ECA03455 -->

<div class="divbox">
	This VIN decoder is specific to Zero electric motorcycles.
	It will decode year, make [Zero], platform and model information, as well as horsepower and possibly other properties of the motorcycle.
	The data was compiled from the information available in the <a href="http://www.zeromotorcycles.com/owner-resources/">owner's manuals</a> 
	which are downloadable from the Zero Motorcycles website.<br />
	<br />
	<form action="?" method="get">
		Enter VIN: <input type="text" value="" name="vin" title="VIN should begin with '538'" />
		<input type="submit" value="Decode VIN" /><br />
		<br />
		* At least the first 12 characters of the VIN are needed.  Serial number, contained in the last 5 characters, is not needed.
		Color information is not contained in the VIN but comes from a list of known colors manufactured each year by model.
	</form>
</div>

<?php
include './vin_decoder.php';

$zvin = $_GET['vin'];

// see if querystring has data
if ( $zvin <> '' ) {
	// run VIN decoder
	$zvin = decode_vin($zvin);
	if ( $zvin <> '' ) {
		// VIN decoder found Zero Motorcycles data
		echo "<table>\r\n";
		echo "<caption>Decode Results for {$zvin['vin']}</caption>\r\n";
		echo "<tr><th>Year</th><td>{$zvin['year']}</td></tr>\r\n";
		echo "<tr><th>Make</th><td>{$zvin['make']}</td></tr>\r\n";
		echo "<tr><th>Type</th><td>{$zvin['type']}</td></tr>\r\n";
		echo "<tr><th>Line</th><td>{$zvin['line']}</td></tr>\r\n";
		echo "<tr><th>Model</th><td>{$zvin['model']}</td></tr>\r\n";
		echo "<tr><th>HP</th><td>{$zvin['hp']}</td></tr>\r\n";
		echo "<tr><th>Plant</th><td>{$zvin['plant']}</td></tr>\r\n";
		echo "<tr><th>Color</th><td>{$zvin['color']}</td></tr>\r\n";
		echo "<tr><th>Valid?</th><td>{$zvin['valid']}</td></tr>\r\n";
		echo "</table>\r\n";
		
		// Record record of decoder usage
		file_put_contents('vindecoder.log', $zvin['vin'].' '.strftime("%F %T")."\r\n", FILE_APPEND);
		
	} else {
		// VIN decoder did not find Zero Motorcycles data
		echo "<br />\r\n<br />\r\nNot a valid Zero Motorcycles VIN.<br />\r\n";
	}
}
?>

<br /><br />
<a href="../">Home</a>

</body>
</html>