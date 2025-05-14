<?php
	//prevents this page from generating too much data on the error log
	error_reporting(E_ERROR | E_PARSE);
	
	//gets the url passed in via POST
	$url = $_POST["url"];
	//gets the printer object passed in via POST
	$printer = $_POST["printer"];
	
	//gets the url of the printer device status page as read only
	$pageDeviceStatus = fopen($url."#rnd".rand(),'r');
	$str = '';
	
	//run through the page and store the HTML data in a string
	if ($pageDeviceStatus) {
		while (!feof($pageDeviceStatus))
			$str .= fgets($pageDeviceStatus);
	}
	
	//print out the string
	echo $str;
	
	//print out the printer data for parsing by the jQuery script
	echo "[printerData]".json_encode($printer)."[/printerData]";
?>
