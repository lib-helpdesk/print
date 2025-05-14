<?php
header('Content-Type: text/html');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Just exit with 200 OK status for preflight requests
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = isset($_POST["url"]) ? $_POST["url"] : null;
$printer = isset($_POST["printer"]) ? $_POST["printer"] : null;

// Provide simulated data that will definitely work
// This is a development/debug feature to ensure the front-end parsing works
$simulateData = isset($_GET["simulate"]) || isset($_POST["simulate"]);

// Create a more complete placeholder HTML with actual data
// This should provide functional feedback even when printers are unreachable
function generateSimulatedHTML($printer) {
    $printerName = isset($printer['name']) ? $printer['name'] : 'Unknown Printer';
    $status = "Ready";
    $tonerLevel = rand(50, 95) . "%";
    $maintenanceLevel = rand(80, 95) . "%";
    $cyanLevel = rand(50, 95) . "%";
    $magentaLevel = rand(50, 95) . "%";
    $yellowLevel = rand(50, 95) . "%";
    
    return <<<HTML
<html><body>
    <!-- Simulated printer data for {$printerName} -->
    <div id="MachineStatus">{$status}</div>
    <div id="SupplyPLR0">{$tonerLevel}</div>
    <div id="SupplyPLR1">{$maintenanceLevel}</div>
    <div id="SupplyPLR2">{$cyanLevel}</div>
    <div id="SupplyPLR3">{$magentaLevel}</div>
    <div id="SupplyPLR4">{$yellowLevel}</div>
    <div id="TrayBinName_1">Tray 1</div>
    <div id="TrayBinStatus_1">OK</div>
    <div id="TrayBinSize_1">Letter</div>
    <div id="TrayBinType_1">Plain</div>
    <div id="TrayBinName_2">Tray 2</div>
    <div id="TrayBinStatus_2">OK</div>
    <div id="TrayBinSize_2">Letter</div>
    <div id="TrayBinType_2">Plain</div>
</body></html>
HTML;
}

// Default fallback HTML for error cases
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

// If simulation mode is enabled, return simulated data
if ($simulateData && $printer) {
    echo generateSimulatedHTML($printer);
    echo "\n\nPrinter Data:\n";
    echo "[printerData]".json_encode($printer)."[/printerData]";
    exit;
}

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
        // Log the error for debugging
        $error_msg = curl_errno($ch) ? curl_error($ch) : "Empty response";
        error_log("Printer fetch error for {$url}: {$error_msg}");
        
        // Use simulated data for development/testing
        if ($simulateData) {
            echo generateSimulatedHTML($printer);
        } else {
            echo $defaultHTML;
        }
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
