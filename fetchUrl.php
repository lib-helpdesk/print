<?php
header('Content-Type: text/html');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = isset($_POST["url"]) ? $_POST["url"] : null;
$printer = isset($_POST["printer"]) ? $_POST["printer"] : null;

if ($url) {
    $str = '';
    $error = false;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Set 5 second timeout
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // Connection timeout of 3 seconds

    // Disable SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // Execute the cURL request
    $str = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = true;
        // Create minimal HTML structure with placeholders for printer status
        $str = '<html><body>
            <div id="MachineStatus">Unable to reach printer</div>
            <div id="SupplyPLR0">N/A</div>
            <div id="SupplyPLR1">N/A</div>
            <div id="SupplyPLR2">N/A</div>
            <div id="SupplyPLR3">N/A</div>
            <div id="SupplyPLR4">N/A</div>
            <div id="TrayBinName_1">Tray 1</div>
            <div id="TrayBinStatus_1">Unavailable</div>
            <div id="TrayBinSize_1">N/A</div>
            <div id="TrayBinType_1">N/A</div>
        </body></html>';
        
        echo $str;
    } else {
        echo $str;
    }

    curl_close($ch);
} else {
    echo "Error: No URL provided.\n";
}

echo "\n\nPrinter Data:\n";
echo "[printerData]".json_encode($printer)."[/printerData]";
?>
