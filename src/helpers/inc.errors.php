<?php 

function arrayPrint($item, $key) {
    echo "<div style='text-align:left;font-size: 16px;font-weight:bold;'>". $key .": ". $item ."</div>";
}
function errorPrint($file, $line, $error) {
    $details = debug_backtrace();
    echo "<div style='margin-left: auto; margin-right: auto;width: 600px;'>";
    echo "<div style='text-align:center;font-size: 32px;font-weight:bold;'>500 Internal Server Error</div><br>";
    if($error != "") {
        echo "<div style='text-align:center;font-size: 16px;font-weight:bold;'>". $error ."</div>";
    }
    echo "<div style='text-align:left;font-size: 18px;font-weight:bold;'>Error Location:</div><br>";
    echo "<div style='text-align:left;font-size: 18px;font-weight:bold;'>".$file."  Line:" .$line."</div><br>";
    echo "<div style='text-align:left;font-size: 18px;font-weight:bold;'>Stack Trace:</div><br>";
    array_walk_recursive($details,'arrayPrint');
    echo "</div>";
    header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
    exit;
}

function logEntry($type,$message,$datasent = "") {
    global $conn;
    $type = $conn->escape_string($type);
    $message = $conn->escape_string($message);
    $datasent = $conn->escape_string($datasent);
    $sql = "INSERT INTO `error_log`(`type`, `message`, `datasent`, `occurrence`) VALUES ('$type','$message','$datasent',NOW())";
    mysqlInsert($sql);
    echo "logged";
}