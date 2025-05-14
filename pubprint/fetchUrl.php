<?php
	error_reporting(E_ERROR | E_PARSE);
	$url = $_POST["url"];
	$printer = $_POST["printer"];
	
	$pageDeviceStatus = fopen($url."#rnd".rand(),'r');
	$str = '';
	
	if ($pageDeviceStatus) {
		while (!feof($pageDeviceStatus))
			$str .= fgets($pageDeviceStatus);
	}
	
	echo $str;
	echo "[printerData]".json_encode($printer)."[/printerData]";
?>
