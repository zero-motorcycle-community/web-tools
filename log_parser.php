<!DOCTYPE html>
<html>
<head>
	<title>Zero Motorcycles Online Log File Parser</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<h1>Zero Motorcycles Log File Parser</h1>

<div class="divbox">
	<form action="./upload.php" method="post" enctype="multipart/form-data">
		Select Zero Motorcycles MBB log file to upload:<br />
		<em>(538VIN_MBB_YYYY-MM-DD.bin)</em><br />
		<br />
		1) <input type="file" name="fileToUpload" id="fileToUpload" /><br />
		<br />
		2) <input type="submit" value="Upload & Parse" name="submit" />
	</form>
</div>

<br />

<div>
	* using Kim Burgess' zero-log-parser script<br />
	<a href="https://github.com/KimBurgess/zero-log-parser">https://github.com/KimBurgess/zero-log-parser</a><br />
	<a href="./python/README.md">README</a><br />
	<br /><br />
	<img src="./images/send-zero-logs.jpg" alt="Zero Motorcycles App screenshot" title="Use the app to download your bike logs" /><br />
	<a href="http://www.zeromotorcycles.com/technology/#app">Download the app</a>
</div>

<br />

<div>
	<a href="./">Home</a><br />
</div>

</body>
</html>
