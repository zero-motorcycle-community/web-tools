<!DOCTYPE html>
<html>
<body>

<?php
$target_dir = "./logs/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// Remove bad filename characters (python script doesn't seem to like them)
// Yes, I know there is a better way to do this in one line; I hate regexp
// Some filename characters like ! @ ~ [ ] seem fine and are left alone
$target_file = str_replace(' ', '', $target_file);
$target_file = str_replace('(', '', $target_file);
$target_file = str_replace(')', '', $target_file);
$target_file = str_replace('&', '', $target_file);
$target_file = str_replace('#', '', $target_file);
$target_file = str_replace('$', '', $target_file);
$target_file = str_replace('%', '', $target_file);
$target_file = str_replace('^', '', $target_file);
$target_file = str_replace('+', '', $target_file);
$target_file = str_replace('=', '', $target_file);
$target_file = str_replace("'", '', $target_file);
$target_file = str_replace(';', '', $target_file);
$target_file = str_replace(',', '', $target_file);


$uploadOk = 1;
$uploadFileSize = $_FILES["fileToUpload"]["size"];
$uploadFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check file size - under 1MB (how big are bin files?)
// I see 128k and 256k files.  Allow 1MB in case Zero makes them larger in future
if ($uploadFileSize > 1048576) {
    echo "Sorry, your file is too large (1MB max). <br />\r\n";
    $uploadOk = 0;
}

// Check for empty file
if ($uploadFileSize == 0) {
	echo "Sorry, your file is empty (0 bytes)! <br />\r\n";
	$uploadOk = 0;
}

// Allow only .bin files
if($uploadFileType != "bin") {
    echo "Sorry, only .bin files are allowed. <br />\r\n";
    $uploadOk = 0;
}

// Disallow _BMS (Battery Management System) logs
// NOTE: MBB logs are named *_MBB_* or *_MbbD_*, and possibly other variants
if( stripos($target_file, '_BMS') > 0 ) {
	echo "Sorry, BMS logs are not currently supported.  Try uploading your MBB log instead.<br />\r\n";
	$uploadOk = 0;
}

// Todo: Could check for fully formatted file names with VIN 538**************_MBB[D]_YYYY-MM-DD.bin
//       BUT some people rename the files when they download, and it's not REALLY necessary to be in that format.

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded. <br />\r\n";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		// SUCCESS UPLOADING FILE
        echo "<p>The file " . basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. </p>\r\n";
		echo "<p>Parsing log file... please wait</p> \r\n";
		// Redirect to parsing script wrapper, send file name via querystring
		echo '<script type="text/javascript">window.location.replace("./parse_log.php?logfile=' . $target_file . '");</script> ' . "\r\n";
    } else {
        echo "Sorry, there was an error uploading your file. \r\n";
    }
}
?>

</body>
</html>