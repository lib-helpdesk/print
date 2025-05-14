<?php
header('Content-Type: text/html');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = isset($_POST["url"]) ? $_POST["url"] : null;
$printer = isset($_POST["printer"]) ? $_POST["printer"] : null;

// Default placeholder HTML for error cases
$defaultHTML = '<html><body>
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

if ($url) {
    $str = '';
    $error = false;

    // Check if cURL is available
    if (!function_exists('curl_init')) {
        echo $defaultHTML;
        echo "\n\nPrinter Data:\n";
        echo "[printerData]".json_encode($printer)."[/printerData]";
        exit;
    }

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

    if (curl_errno($ch) || empty($str)) {
        echo $defaultHTML;
    } else {
        echo $str;
    }

    curl_close($ch);
} else {
    echo $defaultHTML;
}

echo "\n\nPrinter Data:\n";
echo "[printerData]".json_encode($printer)."[/printerData]";
?>
