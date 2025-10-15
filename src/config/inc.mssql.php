<?php
//Connection for MSSQL database.
//Requires the PHP MSSQL Drivers - https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
$connectionInfo = array( "Database"=> MSSQL_DATABASE, "UID"=> MSSQL_USERNAME, "PWD"=> MSSQL_PASS, "TrustServerCertificate"=> true, "CharacterSet" => "UTF-8");
$conn_ms = sqlsrv_connect( MSSQL_HOST, $connectionInfo);

//Validate successful connection
if( $conn_ms ) {
}else{
    echo "Connection could not be established.<br />";
    die( print_r( sqlsrv_errors(), true));
}

// Include DB functions
require_once(ROOT.INC_HELPERS."inc.mssql.php");