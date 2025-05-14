<?php
header('Content-Type: text/html');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$url = isset($_POST["url"]) ? $_POST["url"] : null;
$printer = isset($_POST["printer"]) ? $_POST["printer"] : null;

if ($url) {
    $str = '';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // Disable automatic return to use custom write function
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Disable SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


    // Custom write function to handle data in chunks
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use (&$str) {
        $str .= $data; // Append the received chunk of data to $str
        return strlen($data); // Return the number of bytes processed
    });

    // Execute the cURL request
    curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        echo $str; // Output the accumulated data
    }

    curl_close($ch);
} else {
    echo "Error: No URL provided.\n";
}

echo "\n\nPrinter Data:\n";
echo "[printerData]".json_encode($printer)."[/printerData]";
?>
